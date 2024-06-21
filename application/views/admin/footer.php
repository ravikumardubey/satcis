
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-footer"> </div>
			</div>
		</div> <!-- page-content -->
	</div> <!-- content-wrapper -->

	<!---- Loading Modal ------------->
	<div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1" id="loading_modal">
	    <div class="modal-dialog modal-sm" style="margin: 25% 50%; text-align: center;">
	        <div class="modal-content" style="width: 90px; height: 90px; padding: 15px 25px;">
	            <span class="fa fa-spinner fa-spin fa-3x"></span>
	            <p class="text-danger" style="margin: 12px -12px;">Loading.....</p>
	        </div>
	    </div>
	</div>
</body>
</html>


<script src="<?= base_url('asset/APTEL_files/backlog.js'); ?>"></script> 
<script src="<?= base_url('asset/admin_js_final/jquery.dataTables.min.js'); ?>"></script> 
<script src="<?= base_url('asset/admin_js_final/dataTables.bootstrap4.min.js'); ?>"></script>
<!-- <script src="<?=base_url('asset/admin_js_final/bootstrap.bundle.min.js')?>"></script> -->
<script src="<?=base_url('asset/admin_js_final/bootstrap.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/blockui.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/d3.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/d3_tooltip.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/switchery.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/bootstrap_multiselect.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/moment.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/daterangepicker.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/app.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/dashboard.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/jquery-ui.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script>
<script src="<?= base_url('asset/APTEL_files/jquery-confirm.js');?>"></script>
<script src="<?=base_url('asset/admin_js_final/efiling.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/appcore.js')?>"></script>
<script src="<?= base_url('asset/APTEL_files/hash.js'); ?>"></script>

<script src="<?= base_url('asset/APTEL_files/aes.js'); ?>"></script>

    
<script src="<?= base_url('asset/admin_js_final/popper.min.js'); ?>" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>

<script type="text/javascript">
	var base_url='', salt='';
	base_url ='<?php echo base_url(); ?>';
    salt='<?php echo hash("sha512",strtotime(date("d-m-Y i:H:s"))); ?>';
</script>
