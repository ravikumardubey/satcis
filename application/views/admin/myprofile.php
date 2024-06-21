<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
 <?= form_fieldset('User Profile ').
                        '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
                        '<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
                        ?>
<div class="container">



    <div class="row">
        <div class="col-lg-8 order-lg-2">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Profile</a>
                </li>                                         
            </ul>            
            <?php //echo "<pre>";print_r($userDetail);?>
            <div class="tab-content py-4">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3"><u><b>User Profile</b></u></h5>
                    <div class="row">                   
                        <div class="col-md-6">
                        <?php
                        $fname=isset($userDetail[0]->fname)?$userDetail[0]->fname:'';
                        $lname=isset($userDetail[0]->lname)?$userDetail[0]->lname:'';
                        ?>
                            <h6>Name</h6> <?php echo ucfirst($fname.' '.$lname); ?>
                        </div>
                        <div class="col-md-6">
                            <h6>Gender</h6><?php echo ucfirst(isset($userDetail[0]->gender)?$userDetail[0]->gender:''); ?>
                        </div>
                        <div class="col-md-6">
                            <h6>Address</h6><?php echo ucfirst(isset($userDetail[0]->address)?$userDetail[0]->address:''); ?>
                        </div>
                         <div class="col-md-6">
                            <h6>Country Name</h6><?php echo ucfirst(isset($userDetail[0]->country)?$userDetail[0]->country:''); ?>
                        </div>
                        <div class="col-md-6">
                            <h6>State Name</h6><?php echo isset($userDetail[0]->state)?$userDetail[0]->state:''; ?>
                        </div>
                        <div class="col-md-6">
                            <h6>District Name</h6> <?php echo isset($userDetail[0]->district)?$userDetail[0]->district:''; ?>
                        </div>
                        <div class="col-md-6">
                            <h6> Pincode</h6><?php echo isset($userDetail[0]->pincode)?$userDetail[0]->pincode:''; ?>
                        </div>
                        <div class="col-md-6">
                            <h6>Mobile</h6><?php echo isset($userDetail[0]->mobilenumber)?$userDetail[0]->mobilenumber:''; ?>
                        </div>
                         <div class="col-md-6">
                            <h6>Mobile</h6><?php echo isset($userDetail[0]->email)?$userDetail[0]->email:''; ?>
                        </div>
                        <?php 
                        $idptype=isset($userDetail[0]->idptype)?$userDetail[0]->idptype:'';
                        $idproof_upd=isset($userDetail[0]->idproof_upd)?$userDetail[0]->idproof_upd:'';
                        $id=isset($userDetail[0]->id)?$userDetail[0]->id:'';
                        ?>
                        
                        <div class="col-md-12">
                            <h5 class="mt-2"><span class="fa fa-clock-o ion-clock float-right"></span><u><b>Uploaded Document</b></u></h5>
                            <table class="table table-sm table-hover table-striped">
                                <tbody>                                    
                                    <tr>
                                        <td>
                                            <strong><?php  echo $idptype; ?> </strong> <strong style="float: right;font-color:red"><a target="_blank" href="<?php echo base_url();?>userdetails/<?php echo hash('sha256','.'.$idproof_upd); ?>/<?php echo $id;?>">Download</a></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Doc2</strong> ravi kumar  <strong style="float: right;font-color:red">Download</strong>
                                        </td>
                                    </tr>                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/row-->
                </div>
                
                
                <div class="tab-pane active" id="editprofile">
                zsfgsdgawdghadsgasdgadgadgfadsdgf                
                </div>


            </div>
        </div>
        <div class="col-lg-4 order-lg-1 text-center">
            <img src="<?php echo base_url();?>userdetails/<?php echo hash('sha256','.'.$idproof_upd); ?>/<?php echo $id;?>" class="mx-auto img-fluid img-circle d-block" alt="avatar">

        </div>
    </div>
</div>
 <?= form_fieldset_close(); ?>
  <?php $this->load->view("admin/footer"); ?>