(function ($) {
    'use strict';

    /*[ Wizard Config ]
        ===========================================================*/
    
    try {
        var $validator = $("#js-wizard-form").validate({
            rules: {
                subject: {
                    required: true
                },
                sumwinyear: {
                    required: true
                },
                semwibawin: {
                    required: true
                },
                semwibasum: {
                    required: true
                },
                semwimawin: {
                    required: true
                },
                semwimasum: {
                    required: true
                },
                semsecmwin: {
                    required: true
                },
                semsecmsum: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                re_password: {
                    required: true,
                    minlength: 8,
                    equalTo: '#password'
                }
            },
            messages: {
                subject: {
                    required: "Pflichtfeld"
                },
                sumwinyear: {
                    required: "Pflichtfeld"
                },
                semwibawin: {
                    required: "Pflichtfeld"
                },
                semwibasum: {
                    required: "Pflichtfeld"
                },
                semwimawin: {
                    required: "Pflichtfeld"
                },
                semwimasum: {
                    required: "Pflichtfeld"
                },
                semsecmwin: {
                    required: "Pflichtfeld"
                },
                semsecmsum: {
                    required: "Pflichtfeld"
                },
                password: {
                    required: "Enter password",
                    minlength: "Password must be >= 8 character"
                },
                re_password: {
                    required: "Please confirm your password",
                    minlength: "Password must has >= 8 character",
                    equalTo: "Password doesn't equal to the previous one"
                }
            }
        });
    
        $("#js-wizard-form").bootstrapWizard({
            'tabClass': 'nav nav-pills',
            'nextSelector': '.btn--next',
            'onNext': function(tab, navigation, index) {
                var $valid = $("#js-wizard-form").valid();
                if(!$valid) {
                    $validator.focusInvalid();
                    return false;
                } else {

                    var sumwinyear = $( "#sumwinyear" ).val().substring(0, 3);
                    var modules = ["#wibawin", "#wimawin", "#secmwin", "#wibasum", "#wimasum", "#secmsum"];
                    var semester = $( "#subject" ).val() + sumwinyear;

                    modules.forEach(
                        function (value) {
                            $(value).hide();
                        }
                    );
                    $( "#" + semester ).show();

                    var sem = $( "#sem" + semester ).val();
                    if (sem != null) {
                        //document.write(sem);

                        let xmlhttp = new XMLHttpRequest();

                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                $("#workload").html(this.responseText);

                                $("#workload").ready(function(){
                                    var trans = $(".col.col-5");
                                    var radio = $(".col.col-3")
                                    $("#dual").change(function(){
                                        var dual = $("#dual");

                                        if (dual.is( ":checked" )){
                                            trans.show();
                                        } else {
                                            trans.hide();
                                        }
                                    });

                                    $(".col.col-4").change(function(e){
                                        var str = e.target.name;
                                        var beleg = $("#" + str);
                                        var courseID = str.substring(0, str.length-6);
                                        var transID = courseID + "trans";

                                        if (beleg.is( ":checked" )){
                                            $(".col.col-3."+courseID).find(':input').prop('disabled', false);
                                            $("#"+transID).prop('disabled', false);
                                        } else {
                                            $(".col.col-3."+courseID).find(':input').prop('disabled', true);
                                            $("#"+transID).prop('disabled', true);
                                        }
                                    });

                                    $(".col.col-3").change(function(e){
                                        var courseID = e.target.name;
                                        var radioVal = $(".col.col-3."+courseID).find(':input:checked').val();

                                        $("#"+courseID+"trans").prop('max', radioVal);
                                    });

                                });
                            }
                        };

                        xmlhttp.open("GET","database.php?id="+sem,true);
                        xmlhttp.send();

                    }

                }
            },
            'onTabClick': function (tab, navigation, index) {
                var $valid = $("#js-wizard-form").valid();
                if(!$valid) {
                    $validator.focusInvalid();
                    return false;
                } else {

                    var sumwinyear = $( "#sumwinyear" ).val().substring(0, 3);
                    var modules = ["#wibawin", "#wimawin", "#secmwin", "#wibasum", "#wimasum", "#secmsum"];
                    var semester = $( "#subject" ).val() + sumwinyear;

                    modules.forEach(
                        function (value) {
                            $(value).hide();
                        }
                    );
                    $( "#" + semester ).show();

                    var sem = $( "#sem" + semester ).val();

                    if (sem !== null) {

                        let xmlhttp = new XMLHttpRequest();

                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {

                                $("#workload").html(this.responseText);

                            }
                        };

                        xmlhttp.open("GET","database.php?id="+sem,true);
                        xmlhttp.send();

                    }

                }
            }
    
        });
    
    }
    catch (e) {
        console.log(e)
    }

})(jQuery);