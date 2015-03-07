<?php

class report_post extends report_module
{
	var $mode = 'reportpost';
	var $duplicates = false;
	var $subject_auth = array();

	//
	// Constructor
	//
	function report_post($id, $data, $lang)
	{
		$this->id = $id;
		$this->data = $data;
		$this->lang = $lang;
	}

	//
	// Synchronizing function
	//
	function sync($uninstall = false)
	{
		$sql = 'UPDATE ' . BB_POSTS . '
			SET post_reported = 0';
		if (!DB()->sql_query($sql)) {
			bb_die('Could not reset post reported flag');
		}

		if (!$uninstall) {
			$sql = 'SELECT report_subject
				FROM ' . BB_REPORTS . '
				WHERE report_module_id = ' . $this->id . '
					AND report_status NOT IN(' . REPORT_CLEARED . ', ' . REPORT_DELETE . ')
				GROUP BY report_subject';
			if (!$result = DB()->sql_query($sql)) {
				bb_die('Could not obtain open reports');
			}

			$open_ids = array();
			while ($row = DB()->sql_fetchrow($result)) {
				$open_ids[] = $row['report_subject'];
			}
			DB()->sql_freeresult($result);

			if (!empty($open_ids)) {
				$sql = 'UPDATE ' . BB_POSTS . '
					SET post_reported = 1
					WHERE post_id IN(' . implode(', ', $open_ids) . ')';
				if (!DB()->sql_query($sql)) {
					bb_die('Could not sync post reported flag');
				}
			}
		}
	}

	//
	// Module action: Insert
	//
	function action_insert($report_subject, $report_id, $report_subject_data)
	{
		$sql = 'UPDATE ' . BB_POSTS . '
			SET post_reported = 1
			WHERE post_id = ' . (int)$report_subject;
		if (!DB()->sql_query($sql)) {
			bb_die('Could not update post reported flag #1');
		}
	}

	//
	// Module action: Update status
	//
	function action_update_status($report_subjects, $report_status)
	{
		switch ($report_status) {
			case REPORT_CLEARED:
			case REPORT_DELETE:
				$this->action_delete($report_subjects);
				break;

			default:
				report_prepare_subjects($report_subjects, true);

				$sql = 'UPDATE ' . BB_POSTS . '
					SET post_reported = 1
					WHERE post_id IN(' . implode(', ', $report_subjects) . ')';
				if (!DB()->sql_query($sql)) {
					bb_die('Could not update post reported flag #2');
				}
				break;
		}
	}

	//
	// Module action: Delete
	//
	function action_delete($report_subjects)
	{
		report_prepare_subjects($report_subjects, true);

		$sql = 'SELECT report_subject
			FROM ' . BB_REPORTS . '
			WHERE report_module_id = ' . $this->id . '
				AND report_id NOT IN(' . implode(', ', array_keys($report_subjects)) . ')
				AND report_subject IN(' . implode(', ', $report_subjects) . ')
				AND report_status NOT IN(' . REPORT_CLEARED . ', ' . REPORT_DELETE . ')
			GROUP BY report_subject';
		if (!$result = DB()->sql_query($sql)) {
			bb_die('Could not get open reports');
		}

		$open_ids = array();
		while ($row = DB()->sql_fetchrow($result)) {
			$open_ids[] = $row['report_subject'];
		}
		DB()->sql_freeresult($result);

		if (!empty($open_ids)) {
			$sql = 'UPDATE ' . BB_POSTS . '
				SET post_reported = 1
				WHERE post_id IN(' . implode(', ', $open_ids) . ')';
			if (!DB()->sql_query($sql)) {
				bb_die('Could not update post reported flag #3');
			}
		}

		$clear_ids = array();
		foreach ($report_subjects as $report_subject) {
			if (!in_array($report_subject, $open_ids)) {
				$clear_ids[] = $report_subject;
			}
		}

		if (!empty($clear_ids)) {
			$sql = 'UPDATE ' . BB_POSTS . '
				SET post_reported = 0
				WHERE post_id IN(' . implode(', ', $clear_ids) . ')';
			if (!DB()->sql_query($sql)) {
				bb_die('Could not update post reported flag #4');
			}
		}
	}

	//
	// Returns url to a report subject
	//
	function subject_url($report_subject, $non_html_amp = false)
	{
		$report_subject = (int)$report_subject;
		return 'viewtopic.php?' . POST_POST_URL . '=' . $report_subject . '#' . $report_subject;
	}

	//
	// Returns report subject title
	//
	function subject_obtain($report_subject)
	{
		$sql = 'SELECT t.topic_title, t.topic_first_post_id
			FROM ' . BB_POSTS . ' p
			INNER JOIN ' . BB_POSTS_TEXT . ' pt
				ON pt.post_id = p.post_id
			INNER JOIN ' . BB_TOPICS . ' t
				ON t.topic_id = p.topic_id
			WHERE p.post_id = ' . (int)$report_subject;
		if (!$result = DB()->sql_query($sql)) {
			bb_die('Could not obtain report subject');
		}

		$row = DB()->sql_fetchrow($result);
		DB()->sql_freeresult($result);

		if (!$row) {
			return false;
		}

		if ($row['topic_title'] != '') {
			return $row['topic_title'];
		} else {
			$subject = ($row['topic_first_post_id'] == $report_subject) ? '' : 'Re: ';
			$subject .= $row['topic_title'];

			return $subject;
		}
	}

	//
	// Obtains additional subject data
	//
	function subject_data_obtain($report_subject)
	{
		$sql = 'SELECT forum_id
			FROM ' . BB_POSTS . '
			WHERE post_id = ' . (int)$report_subject;
		if (!$result = DB()->sql_query($sql)) {
			bb_die('Could not obtain report subject data');
		}

		$row = DB()->sql_fetchrow($result);
		DB()->sql_freeresult($result);

		return $row;
	}

	//
	// Obtains subjects authorisation
	//
	function subjects_auth_obtain($user_id, $report_subjects)
	{
		report_prepare_subjects($report_subjects);
		$moderated_forums = user_moderated_forums($user_id);

		//
		// Check stored forum ids
		//
		$check_posts = array();
		foreach ($report_subjects as $report_subject) {
			if (in_array($report_subject[1]['forum_id'], $moderated_forums)) {
				$this->subjects_auth[$user_id][$report_subject[0]] = true;
			} else {
				$this->subjects_auth[$user_id][$report_subject[0]] = false;

				$check_posts[] = $report_subject[0];
			}
		}

		//
		// Check current forum ids
		//
		if (!empty($check_posts)) {
			$sql = 'SELECT post_id, forum_id
				FROM ' . BB_POSTS . '
				WHERE post_id IN(' . implode(', ', $check_posts) . ')';
			if (!$result = DB()->sql_query($sql)) {
				bb_die('Could not obtain current forum ids');
			}

			while ($row = DB()->sql_fetchrow($result)) {
				if (in_array($row['forum_id'], $moderated_forums)) {
					$this->subjects_auth[$user_id][$row['post_id']] = true;
				}
			}
			DB()->sql_freeresult($result);
		}
	}
}