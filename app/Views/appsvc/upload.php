<div class="main-container">
    <div class="main-content">
    <p id="category_name" hidden><?=$cat_name?></p>
        <form id="upload-form" method="post" enctype="multipart/form-data" action="/<?=$cat_name?>/upload">
            <table class="layout-table">
                <colgroup>
                    <col style="width: 200px;" />
                    <col style="width: 200px;" />
                    <col style="width: 500px;" />
                    <col style="width: 200px;" />
                </colgroup>
            
                <tr><td></td>
                    <td><label>업로드파일:</label></td>
                    <td><input type="file" accept=".zip" id="upload-file" name="upload-file" style="width:300px;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><label>업로드버전:</label></td>
                    <td><input type="text" id="upload-version" name="upload-version" style="width:200px;" value="<?=$last_version?>">
                        <?php if(strlen($last_version) > 0) { ?><br><br>
                        <label>(최신버전은 <?=$last_version?>입니다.)</label>
                        <?php } else { ?>
                        <label>(최신버전이 없습니다.)</label>
                        <?php } ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <hr />
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><input type="submit" class="form-button" value="업로드">
                        <a class="form-button" style="padding:7px 30px;" href = "/<?=$cat_name?>/updatehistory">취소</a></td>
                    <td></td>
                </tr>
            </table>
        </form>
    
    
    </div>
</div>

