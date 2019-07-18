<style type="text/css">
    hr {
        border: 1px solid #827967;
    }

    .container {
        padding-top: 100px;
    }
    .login_title{
        color: #351d0f;
    }
    .register{
        color: #d65f30;
        text-decoration: underline;
    }
    .register:hover{
        color: #d65f30;
    }
    .forget{
        color: #d65f30;
    }
    .forget:hover{
        color: #d65f30;
        text-decoration: none;
    }
    .col-form-label{
        color: #666666;
        font-size: 18px;
        text-align: justify;　/*　Firefox到此即可對齊　*/
        text-justify: distribute-all-lines;
        text-align-last: justify;
    }
    .login_button{
        background-color: #db5523;
        color: #fff;
        border-radius: 30px;
    }
    .register_form .form-control{
        border-color: #c8c5be;
        background-color: transparent;
    }
    .messages{
        color: red;
    }
    img {
        padding-top: 16px;
        padding-bottom: 16px;
    }
</style>
<div class="container">
    <div class="text-center">
        <h3 class="login_title">會員登入</h3>
    </div>
    <hr>
    <div id="error_msg">
        <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== '') : ?>
            <div class="alert alert-danger">
                <?php echo Yii::app()->session['error_msg']; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="success_msg">
        <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== '') : ?>
            <p class="alert alert-success">
                <?php echo Yii::app()->session['success_msg']; ?>
            </p>
        <?php endif; ?>
    </div>
    <form role="form" class="col-lg-8 mx-auto mt-5 register_form" action="<?php echo Yii::app()->createUrl('site/register'); ?>" method="post" accept-charset="utf-8" id="register_form">
        <div class="form-group row">
            <label for="account" class="col-sm-3 col-form-label">會員帳號(Email)</label>
            <div class="col-sm-7">
                <input type="email" class="form-control" id="account" name="account" value="<?= $data['account'] ?>" placeholder="account" required>
            </div>
            <label class="col-sm-2 col-form-label" style="color:red;font-size: 14px;">必填*</label>
            <div class="messages"></div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">姓名</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="name" name="name" value="<?= $data['name'] ?>" placeholder="姓名" required>
            </div>
            <label class="col-sm-2 col-form-label" style="color:red;font-size: 14px;">必填*</label>
            <div class="messages"></div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-3 col-form-label">密碼</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
            </div>
            <label class="col-sm-2 col-form-label" style="color:red;font-size: 14px;">必填*</label>
            <div class="messages"></div>
        </div>
        <div class="form-group row">
            <label for="password_confirm" class="col-sm-3 col-form-label">確認密碼</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="請再次輸入密碼" required>
            </div>
            <label class="col-sm-2 col-form-label" style="color:red;font-size: 14px;">必填*</label>
            <div class="col-sm-3"></div>
            <div class="col-sm-7 messages"></div>
        </div>
        <div class="form-group row">
            <label for="sex" class="col-sm-3 col-form-label">性別</label>
            <div class="col-sm-7 my-auto">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="F" value="F" <?php echo ($data['gender'] == 'F') ? 'checked="checked"' : '' ?>>
                    <label class="form-check-label" for="F">女</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="M" value="M" <?php echo ($data['gender'] == 'M') ? 'checked="checked"' : '' ?>>
                    <label class="form-check-label" for="M">男</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-3 col-form-label">電話</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="phone" name="phone" value="<?= $data['phone'] ?>" placeholder="請輸入電話" value="">
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-sm-3 col-form-label">行動電話</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="mobile" value="<?= $data['mobile'] ?>" placeholder="請輸入行動電話" value="">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">生日</label>
            <div class="col-sm-7">
                <input id="birthdate" class="form-control" type="date" value="<?= $data['birthday'] ?>" placeholder="YYYY-MM-DD" name="birthday">
            </div>
        </div>

        <div class="form-group row">
            <label for="nationality" class="col-sm-3 col-form-label">國別</label>
            <div class="col-sm-7">
                <select class="selectpicker countrypicker form-control" id="nationality" name="nationality" data-default="TW" value="<?= $data['nationality'] ?>"></select>
            </div>
        </div>

        <div class="form-group row">
            <label for="county" class="col-sm-3 col-form-label">縣市</label>
            <div class="col-sm-7">
                <div id="twzipcode" class="col-form-label"></div>
            </div>
        </div>

        <div class="form-group row">
            <label for="address" class="col-sm-3 col-form-label">地址</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="address" name="address" placeholder="請輸入詳細地址" value="<?= $data['address'] ?>">
            </div>
        </div>
        <div class="form-group col-lg-12 text-center my-5">
            <button type="submit" class="btn col-sm-8 col-md-6 col-xl-4 col-lg-4 login_button btn-lg">註冊</button>
        </div>
    </form>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/twzipcode.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap-select-country/dist/js/bootstrap-select-country.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/validator/validate_v2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script>
    $(document).ready( function() {
        $('#twzipcode').twzipcode({
            zipcodeIntoDistrict: true,
            css: ["county form-control d-inline-block w-auto mr-2", "town form-control d-inline-block w-auto"],
            countyName: "county",
            districtName: "town"
        });
        $("#twzipcode").twzipcode("set", {
            "county": "<?php echo $data['county']; ?>",
            "district": "<?php echo $data['town']; ?>"
        });
        validate.extend(validate.validators.datetime, {
            parse: function(value, options) {
              return +moment.utc(value);
            },
            format: function(value, options) {
              var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
              return moment.utc(value).format(format);
            }
        });
        var constraints = {
            "email": {  
                presence:  {
                    message: "是必填的欄位"
                }, // Email 是必填欄位
                email: true // 需要符合 email 格式
            },
            "password": {
                presence: {
                    message: "是必填的欄位"
                }, // 密碼是必填欄位
                length: {
                    // minimum: 5, // 長度大於 ５
                    // maximum: 12, // 長度小於 12
                    // message: "^密碼長度需大於 5 小於 12"
                },
            },
            "password_confirm": {  
                presence: {
                    message: "是必填的欄位"
                },// 確認密碼是必填欄位
                equality: {
                    attribute: "password",// 此欄位要和密碼欄位一樣
                    message: "^密碼不相同"
                }
            },
            "name": {
                presence: {
                    message: "是必填的欄位"
                }, // 必填使用者名稱
                length: {
                    minimum: 3, // 名稱長度要超過 3 
                    maximum: 32, // 長度小於 12
                },
            },
        };

        // Hook up the form so we can prevent it from being posted
        var form = document.querySelector("#register_form");
      
        // 監聽 input 值改變的狀況
        var inputs = document.querySelectorAll("input, textarea, select")
        for (var i = 0; i < inputs.length; ++i) {
            inputs.item(i).addEventListener("change", function(ev) {
                var errors = validate(form, constraints) || {};
                showErrorsForInput(this, errors[this.name])
            });
        }

        // Updates the inputs with the validation errors
        function showErrors(form, errors) {
            // We loop through all the inputs and show the errors for that input
            _.each(form.querySelectorAll("input[name], select[name]"), function(input) {
                // Since the errors can be null if no errors were found we need to handle
                // that
                showErrorsForInput(input, errors && errors[input.name]);
            });
        }

        // Shows the errors for a specific input
        function showErrorsForInput(input, errors) {
            // This is the root of the input
            var formGroup = closestParent(input.parentNode, "form-group")
              // Find where the error messages will be insert into
              , messages = formGroup.querySelector(".messages");
            // First we remove any old messages and resets the classes
            resetFormGroup(formGroup);
            // If we have errors
            if (errors) {
                // we first mark the group has having errors
                formGroup.classList.add("has-error");
                // then we append all the errors
                _.each(errors, function(error) {
                    addError(messages, error);
                });
            } else {
                // otherwise we simply mark it as success
                formGroup.classList.add("has-success");
            }
        }

        // Recusively finds the closest parent that has the specified class
        function closestParent(child, className) {
            if (!child || child == document) {
                return null;
            }
            if (child.classList.contains(className)) {
                return child;
            } else {
                return closestParent(child.parentNode, className);
            }
        }

        function resetFormGroup(formGroup) {
            // Remove the success and error classes
            formGroup.classList.remove("has-error");
            formGroup.classList.remove("has-success");
            // and remove any old messages
            _.each(formGroup.querySelectorAll(".help-block.error"), function(el) {
                el.parentNode.removeChild(el);
            });
        }

        // Adds the specified error with the following markup
        // <p class="help-block error">[message]</p>
        function addError(messages, error) {
            var block = document.createElement("p");
            block.classList.add("help-block");
            block.classList.add("error");
            block.innerText = error;
            console.log(block);
            messages.appendChild(block);
        }
        function showSuccess() {
            alert("Success!"); // We made it \:D/
        }
    });
</script>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>