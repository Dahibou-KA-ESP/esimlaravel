                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Assurpro is an online Insurance Agency Management System" name="description">
    <meta content="XSOFT" name="author">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>{{ trans('setup.title') }} - {{ config('insura.name') }} | Insurance Agency Management System</title>
    <base href="{{ env('BASE_HREF', '') }}" />
    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon" sizes="16x16" type="image/x-icon">
    <link href="{{ asset('uploads/images/' . config('insura.favicon')) }}" rel="icon" type="{{ storage_path() . '/app/images/' . config('insura.favicon') }}">
    <!-- Font and Icon Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700" rel="stylesheet">
    <link href="{{ asset('assets/fonts/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <!-- Core CSS -->
    <link href="{{ asset('assets/libs/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/semantic-UI/semantic.min.css') }}" rel="stylesheet">
    <!-- Page Specific CSS -->
    <link href="{{ asset('assets/libs/datepicker/datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/dropify/css/dropify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dashboard/inscription.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/intl-tel-input/css/intlTelInput.css') }}" rel="stylesheet"/>
    <!-- Custom CSS -->
    
    <style>
    	div.page-title {
    		text-align: center;
    	}
        div.message p > span {
            margin: -.2em 0 0 .2em;
            color: #db2828;
        }
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="body-inscription">

<div class="container">
    <div class="card">
        <div class="form">
            <div class="left-side">
                <div class="left-heading">
                    <img class="logo" src="https://thehostels.com/uploads/rs/landimages/1/logo2.png">
                </div>
                <div class="steps-content">
                    <h3>Шаг <span class="step-number">1</span></h3>
                    <p class="step-number-content active">Заполните свою личную информацию.</p>
                    <p class="step-number-content d-none">Get to know better by adding your diploma,certificate and
                        education life.</p>
                    <p class="step-number-content d-none">Help companies get to know you better by telling then about
                        your past experiences.</p>
                    <p class="step-number-content d-none">Add your profile piccture and let companies find youy fast.
                    </p>
                </div>
                <ul class="progress-bar">
                    <li class="active">Личные данные</li>
                    <li>
                        <p>Организация</p>
                    </li>
                    <li>
                        <p>Фотография</p>
                    </li>
                    <li>
                        <p>Подтверждение</p>
                    </li>
                </ul>



            </div>
            <div class="right-side">
                <div class="main active">
                    <div class="text">
                        <h2>Личные данные</h2>
                        <p>Пожалуйста, представьтесь. Будем рады знакомству с Вами!</p>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required require id="user_name">
                            <span>Ваше имя</span>
                        </div>
                        <div class="input-div">
                            <input type="text" required>
                            <span>Ваша фамилия</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required require>
                            <span>Номер телефона</span>
                        </div>
                        <div class="input-div">
                            <input type="text" required require>
                            <span>E-mail адрес</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <select>
                                <option>Гражданство</option>
                                <option>Россия</option>
                                <option>Индия</option>
                                <option>Китай</option>
                                <option>США</option>
                                <option>Иран</option>
                                <option>Ирак</option>
                                <option>ОАЭ</option>
                                <option>Пакистан</option>
                                <option>Южная Корея</option>
                            </select>

                        </div>
                        <div class="input-div">

                            <select>
                                <option>Форма организации</option>
                                <option>Юридическое лицо</option>
                                <option>Индивидуальный предприниматель</option>
                                <option>Самозанятое лицо</option>
                            </select>
                        </div>
                    </div>
                    <div class="buttons">
                        <button class="next_button">Назад</button>
                        <button class="next_button">Дальше</button>
                    </div>
                </div>
                <div class="main">
                    <small><i class="fa fa-smile-o"></i></small>
                    <div class="text">
                        <h2>Education</h2>
                        <p>Inform companies about your education life.</p>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required require>
                            <span>School Name</span>
                        </div>
                        <div class="input-div">
                            <input type="text" required>
                            <span>Board Name</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required require>
                            <span>College/University name</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <select>
                                <option>Select Course</option>
                                <option>BCA</option>
                                <option>B-TECH</option>
                                <option>BA</option>
                                <option>B-COM</option>
                                <option>B-SC</option>
                                <option>MBA</option>
                                <option>MCA</option>
                                <option>M-COM</option>
                                <option>M-TECH</option>
                            </select>
                        </div>
                    </div>
                    <div class="buttons button_space">
                        <button class="back_button">Back</button>
                        <button class="next_button">Next Step</button>
                    </div>
                </div>
                <div class="main">
                    <small><i class="fa fa-smile-o"></i></small>
                    <div class="text">
                        <h2>Work Experiences</h2>
                        <p>Can you talk about your past work experience?</p>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required require>
                            <span>Experience 1</span>
                        </div>
                        <div class="input-div">
                            <input type="text" required require>
                            <span>Position</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required>
                            <span>Experience 2</span>
                        </div>
                        <div class="input-div">
                            <input type="text" required>
                            <span>Position</span>
                        </div>
                    </div>
                    <div class="input-text">
                        <div class="input-div">
                            <input type="text" required>
                            <span>Experience 3</span>
                        </div>
                        <div class="input-div">
                            <input type="text" required>
                            <span>Position</span>
                        </div>
                    </div>
                    <div class="buttons button_space">
                        <button class="back_button">Back</button>
                        <button class="next_button">Next Step</button>
                    </div>
                </div>



                <div class="main">
                    <small><i class="fa fa-smile-o"></i></small>
                    <div class="text">
                        <h2>User Photo</h2>
                        <p>Upload your profile picture and share yourself.</p>
                    </div>
                    <div class="user_card">
                        <span></span>
                        <div class="circle">
                            <span><img src="https://i.imgur.com/hnwphgM.jpg"></span>

                        </div>
                        <div class="social">
                            <span><i class="fa fa-share-alt"></i></span>
                            <span><i class="fa fa-heart"></i></span>

                        </div>
                        <div class="user_name">
                            <h3>Peter Hawkins</h3>
                            <div class="detail">
                                <p><a href="#">Izmar,Turkey</a>Hiring</p>
                                <p>17 last day . 94Apply</p>
                            </div>
                        </div>
                    </div>
                    <div class="buttons button_space">
                        <button class="back_button">Back</button>
                        <button class="submit_button">Submit</button>
                    </div>
                </div>
                <div class="main">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                    </svg>

                    <div class="text congrats">
                        <h2>Congratulations!</h2>
                        <p>Thanks Mr./Mrs. <span class="shown_name"></span> your information have been submitted
                            successfully for the future reference we will contact you soon.</p>
                    </div>
                </div>






            </div>
        </div>
    </div>
</div>
    <!-- Core Scripts -->
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/inscription.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/semantic-UI/semantic.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/insura.js') }}" type="text/javascript"></script>
    <!-- Page Specific Scripts -->	
    <script src="{{ asset('assets/libs/datepicker/datepicker.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/libs/datepicker/datepicker-fr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/dropify/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/intl-tel-input/js/intlTelInput.min.js') }}" type="text/javascript"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initDatepicker('input.datepicker');
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initDropify('input.file-upload');
                $insura.helpers.initTabs('div.insura-tabs a.item');
                $insura.helpers.initTelInput('input[type="tel"]');
                $insura.helpers.requireDropdownFields('form div.required select');

                // Navigate Tabs
                $('button[data-toggle="tab"]').click(function() {
                	var tab = $(this).attr('data-target');
                	$('div.insura-tabs a.item[data-tab="' + tab + '"]').click();
                	$('div.tab[data-tab="' + tab + '"] input:first').focus();
                });

                // Toggle Database Driver
                $('select[name="db_connection"]').change(function(event) {
                	$('div.db').each(function(i, e) {
                		var field = $(e);
                		if(field.hasClass(event.target.value)) {
                			field.show();
                		}else {
                			field.hide();
                		}
                	});
                }).change();

                // Toggle Mail Driver
                $('select[name="mail_driver"]').change(function() {
                    var select = $(this).fadeOut(100);
                    $('div.field.mail').attr('required', false).hide();
                    $('div.mail.' + select.val()).attr('required', true).fadeIn(200);
                }).change();

                // Toggle Text Providers
                $('select[name="text_provider"]').change(function() {
                    var element = $(this);
                    var parentTab = element.parents('div.segment.tab:first'),
                        value = element.val();
                    if(value === 'aft') {
                        parentTab.find('input[name^="aft_"]').attr('required', true).parent().fadeIn();
                    }else {
                        parentTab.find('input[name^="aft_"]').attr('required', false).parent().fadeOut();
                    }
                    if(value === 'twilio') {
                        parentTab.find('input[name^="twilio_"]').attr('required', true).parent().fadeIn();
                    }else {
                        parentTab.find('input[name^="twilio_"]').attr('required', false).parent().fadeOut();
                    }
                }).change();
            });
        })(window.insura, window.jQuery);
    </script>

</body>
</html>
