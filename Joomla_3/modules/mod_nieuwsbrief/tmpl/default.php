<?php 
// No direct access
defined('_JEXEC') or die; ?>
<form action="<?php echo JUri::base(); ?>index.php?option=com_engine" method="post" name="adminForm" id="form-validate" class="form-validate form-contact contact-form">
<div class="container">
    <div class="row">

            
            <div class="col-sm">
                <div class="row">
                    <div class="col-md-2">
                        img
                    </div>
                    <div class="col-sm">
                        <div class="col-md-8">
                            <div class="col">
                                <h3> 
                                    Nieuwsbrief & Acties
                                </h3>
                            </div>
                            <div class="col">
                                <p> 
                                    Vermeldt uw mail om acties te ontvangen!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="row">
<!--                    <div class="col-6">
                        <input class="input-group input-group-lg" type="text" name="email" value="Vermeld uw mail hier...">
                    </div>
                    <div style="background-color: orange" class="col">
                        <input class="btn btn-warning btn-lg active" type="submit" name="submit" value="Submit">
                    </div>-->
                    <div class="col-8">
                    <div class="input-group">
                        <input class="form-control" type="text" name="email" value="Vermeld uw mail hier...">
                        <div class="input-group-append">
                            <span "class="input-group-text">
                                <input class="btn btn-warning active" type="submit" name="submit" value="Submit">
                            </span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            

    </div>
</div>
</form>


