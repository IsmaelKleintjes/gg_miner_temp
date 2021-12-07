/* 
	CREATED BY EDIT4U WEBSERVICES B.V.
*/

var $ = jQuery.noConflict();

jQuery(document).ready(function() {
	validateForm();
    photoUpload();
    displayName();
    enterSubmitForm();
    loadDatepickers();
    //loadChosen();
    loadSelect2();
    timepickerDepend();
    pagePreloader();
    loadMasks();
    //overrideConfirm();
});

function loadChosen()
{
    if(!jQuery().chosen){
        return;
    }

    jQuery('.chosen').chosen({
        placeholder_text_multiple: ' - Selecteer - ',
        width: '100%',
        no_results_text: 'Er zijn geen resultaten gevonden.'
    });
}

function loadSelect2()
{
    if(!jQuery().select2){
        return;
    }

    jQuery('.chosen').each(function(){ // Ja .chosen - omdat we de class altijd handteren en makkelijk vendor makkelijk te vervangen is :-)
        jQuery(this)
            .wrap('<div class="dropdown-container"></div>')
            .select2({
                dropdownParent: jQuery(this).parent()
            })
        ;
    });
}


function loadDatepickers()
{
    if(!jQuery().datepicker){
        return;
    }
    jQuery('.datepicker').datepicker({
        language: 'nl',
        autoclose: true,
        clearBtn: true
    });

    //jQuery(".datepicker").after('<i class="ion ion-md-calendar datepicker-calender"></i>');
}

function timepickerDepend()
{
    timeEndDepend();
    timeStartDepend();

    jQuery(document).on('change', '.time-end', function(){
        timeEndDepend();
    });

    jQuery(document).on('change', '.time-start', function(){
        timeStartDepend();
    });
}

function timeEndDepend()
{
    var $timeEnd = jQuery('.time-end');

    var $timeStart = jQuery('.time-start');

    if(!$timeStart.length || !$timeEnd.length){
        return;
    }

    var timeEnd = $timeEnd.val();

    if(typeof timeEnd !== 'undefined' && timeEnd.length > 0){
        $timeStart.timepicker('option', { minTime: '00:00', maxTime: timeEnd });
    } else {
        $timeStart.timepicker('option', { minTime: '00:00', maxTime: '24:00' });
    }
}

function timeStartDepend()
{
    var $timeEnd = jQuery('.time-end');

    var $timeStart = jQuery('.time-start');

    if(!$timeStart.length || !$timeEnd.length){
        return;
    }

    var timeStart = $timeStart.val();

    if(typeof timeStart !== 'undefined' && timeStart.length > 0){
        $timeEnd.timepicker('option', { minTime: timeStart, maxTime: '24:00' });
    } else {
        $timeEnd.timepicker('option', { minTime: '00:00', maxTime: '24:00' });
    }
}

function displayName() {
    jQuery(document).on('keyup', '.firstname', function(){
        jQuery('#user_firstname').html(jQuery(this).val());
    });

    jQuery(document).on('keyup', '.lastname', function(){
        jQuery('#user_lastname').html(jQuery(this).val());
    });
}

function validateForm() {

    jQuery(".form-validate").each(function(){
        jQuery(this).validate({ ignore: '.ignore'});
    });

    jQuery.validator.addMethod("postal-field", function (value, element) {
        return this.optional(element) || /^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i.test(value);
    }, 'Vul een correcte postcode in.');
}

function photoUpload() {
    if(!jQuery().croppie){
        return;
    }

    var $uploadCrop;
    var croppieFilename;
    var base64NoCrop;

    function readFile(input, doCrop) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                croppieFilename = input.files[0].name;

                jQuery('.upload-photo').addClass('ready');


                jQuery('.upload-photo-wrap').show();
                jQuery('.upload-photo-image').hide();
                jQuery('.btn-save-photo').show();
                jQuery('.btn-save-photo-temp').show();

                if(doCrop === 1) {
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    });

                    base64NoCrop = e.target.result;
                } else {
                    base64NoCrop = e.target.result;
                    var htmlImage = '<img class="img-fluid" src="' + e.target.result + '" />';
                    jQuery('#upload-photo').html(htmlImage);
                }
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    var boundaryWidth = jQuery('.photo-wrap').width();
    var circleWidth = boundaryWidth - 25;

    $uploadCrop = jQuery('#upload-photo').croppie({
        viewport: {width: circleWidth, height: circleWidth, type: 'circle'},   // The inner container of the coppie.
        boundary: {width: boundaryWidth, height: boundaryWidth},   // The outer container of the cropper
        enableExif: true
    });


    jQuery('#photo').on('change', function () { readFile(this, 1); });

    jQuery(document).on('click', '.btn-save-photo', function(){
        jQuery('.upload-photo-button').addClass('disabled').attr('disabled', 'disabled');

        var userId = jQuery(this).data('id');

        $uploadCrop.croppie('result', {
            type: 'base64',
            size: { width: 400, height: 400},
            format: 'png',
            circle: true
        }).then(function (res) {
            jQuery.when(jQuery.ajax({
                url: base + 'index.php?option=com_engine',
                type: 'POST',
                data: {
                    task: 'prphoto.save',
                    format: 'json',
                    id: userId,
                    filename: croppieFilename,
                    photo: res,
                    original_photo: base64NoCrop
                }
            })).done(function(response){
                jQuery('.upload-photo-button').removeClass('disabled').removeAttr('disabled');

                var result = jQuery.parseJSON(response);

                if(result.success){

                    jQuery('.upload-photo-wrap').hide();
                    jQuery('.upload-photo-image').show();
                    jQuery('.btn-save-photo').hide();

                    jQuery('#profile-image').attr('src', base + result.data.photo);
                }
            });
        });
    });

    jQuery(document).on('click', '.btn-save-photo-temp', function(){
        jQuery('.upload-photo-button').addClass('disabled').attr('disabled', 'disabled');

        $uploadCrop.croppie('result', {
            type: 'base64',
            size: { width: 400, height: 400},
            format: 'png',
            circle: true
        }).then(function (res) {
            jQuery.when(jQuery.ajax({
                url: base + 'index.php?option=com_engine',
                type: 'POST',
                data: {
                    task: 'prphoto.saveTemp',
                    format: 'json',
                    id: 0,
                    filename: croppieFilename,
                    photo: res,
                    original_photo: base64NoCrop
                }
            })).done(function(response){
                jQuery('.upload-photo-button').removeClass('disabled').removeAttr('disabled');

                var result = jQuery.parseJSON(response);

                console.log(result);
                if(result.success){

                    jQuery('.upload-photo-wrap').hide();
                    jQuery('.upload-photo-image').show();
                    jQuery('.btn-save-photo-temp').hide();

                    jQuery('#profile-image').attr('src', base + result.data.photo);
                    jQuery('#jform_photo').val(result.data.photo);
                    jQuery('#jform_original_photo').val(result.data.original_photo);
                }
            });
        });
    });
}

function enterSubmitForm()
{
    jQuery('#adminForm').keypress(function(event) {
        var keycode = event.keyCode || event.which;
        if(keycode == '13') {
            jQuery('#btn-submit-form').click();
        }
    });
}

function pagePreloader()
{
    jQuery("form").submit(function (e) {
        if(jQuery(this).valid()) {
            jQuery('.fullpage-loader').show();
        }
    });

    jQuery(".navbar .ion-md-add, .action-group a, table td.col-clickable").click(function(){
        jQuery('.fullpage-loader').show();
    });
}

function loadMasks()
{
    if(!jQuery().mask){
        return;
    }

    jQuery('.postal-field').mask("0000 SS", {placeholder: "____ __"});

    jQuery('.postal-field').keyup(function(){
        jQuery(this).val(jQuery(this).val().toUpperCase());
    });
}
/*function overrideConfirm()
{
    window.confirm = function (message, callback, caption) {
        caption = caption || 'Confirmation';

        Swal.fire({
            title: message,
            //text: 'You will not be able to recover this imaginary file!',
            icon: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonText: 'Ja, verwijderen!',
            cancelButtonText: 'Nee, niet verwijderen!'
        }).then(function(result) {
            if (result.value) {
                Swal.fire('Verwijderd!', 'Die zien we nooit meer - terug!', 'success');
                callback();
            } else {
                jQuery('.fullpage-loader').hide();
            }
        });

    };
}
*/
