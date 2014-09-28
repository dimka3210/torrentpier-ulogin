<style type="text/css">
    #newBBCodeTable input[type=text], textarea {
        width: 90% !important;
    }

    .required {
        color: red;
    }
</style>

<table class="forumline">
    <colgroup class="row1"></colgroup>
    <colgroup class="row2"></colgroup>
    <colgroup class="row3"></colgroup>
    <colgroup class="row4"></colgroup>
    <colgroup class="row1"></colgroup>
    <tbody>
    <tr>
        <th colspan="5">Настройки bb-code</th>
    </tr>
    <!-- IF IS_ROWS -->
    <tr>
        <th>Код</th>
        <th>Регулярка</th>
        <th>HTML</th>
        <th>Доступ</th>
        <th>Действия</th>
    </tr>
    <!-- BEGIN codes -->
    <tr>
        <td>{codes.CODE}</td>
        <td>{codes.REGEXP}</td>
        <td>{codes.OUT_HTML}</td>
        <td>{codes.USER_LEVEL}</td>
        <td>
            <a href="/admin/admin_bbcode.php?mode=edit&id={codes.ID}">Редактировать</a> /
            <a href="/admin/admin_bbcode.php?mode=delete&id={codes.ID}">Удалить</a>
        </td>
    </tr>
    <!-- END codes -->
    <!-- ELSE -->
    <tr>
        <td colspan="12" class="tCenter"><h4>Пока нет ни одного bb-кода</h4></td>
    </tr>
    <!-- ENDIF -->

    </tbody>
</table>

<form method="post" name="bb-code">
    <table class="forumline" id="newBBCodeTable">
        <colgroup class="row1"></colgroup>
        <colgroup class="row2"></colgroup>
        <tr>
            <th colspan="2">Добавить новый код</th>
        </tr>
        <tr>
            <td>Код <span class="required">*</span></td>
            <td><input type="text" name="NewCode[code]" placeholder="[tag][/tag]"></td>
        </tr>
        <tr>
            <td>Регулярное выражение <span class="required">*</span>
                <a href="http://www.php.su/articles/?cat=regexp&page=008" target="_blank"><span class="">Что это?</span></a>
            </td>
            <td><input type="text" name="NewCode[reg_exp]" placeholder="\[tag\](.*)\[\/tag\]"></td>
        </tr>
        <tr>
            <td>HTML <span class="required">*</span></td>
            <td><input type="text" name="NewCode[out_html]" placeholder="<span class='tag'>\\1</span>"></td>
        </tr>
        <tr>
            <td>Доступ</td>
            <td>
                <select name="NewCode[user_level]">
                    <option value="0">Пользователям</option>
                    <option value="1">Модераторам</option>
                    <option value="2">Админам</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Описание</td>
            <td><textarea name="NewCode[description]" rows="3"></textarea></td>
        </tr>
        <tr>
            <td>Мин. сообщений</td>
            <td><input type="text" name="NewCode[min_posts]" value="0"></td>
        </tr>
        <tr>
            <td>Активировать</td>
            <td class="row1">
                <input type="radio" value="0" name="NewCode[is_enabled]"> Нет
                <input type="radio" value="1" name="NewCode[is_enabled]" checked> Да
            </td>
        </tr>
        <tr>
            <td>Регистр</td>
            <td class="row1">
                <input type="radio" value="0" name="NewCode[case_sensitivity]"> Нет
                <input type="radio" value="1" name="NewCode[case_sensitivity]" checked> Да
            </td>
        </tr>
        <tr>
            <td colspan="2" class="tCenter">
                <input type="submit">
                <input type="reset">
            </td>
        </tr>
    </table>
</form>