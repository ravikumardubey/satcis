<!-- <a href="javascript:void(0);" data-toggle="modal" data-target=".modal_1">open modal</a> -->
 <?php error_reporting(); ?>
<div class="modal fade modal_1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg" style="min-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="mheading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div> 
            <div class="modal-body">
                <div class="body-message">
                
                    <table id="modal_dataTable" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Reference No</th>
                                    <th>Party</th>
                                    <th>bench</th>
                                    <th>Sub bench</th>
                                    <th>Year</th>
                                    <th>Last Update</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="data-body">
                            </tbody>
                        </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>    
        </div>    
    </div>  
</div>

<style>
table.dataTable.nowrap td {
    white-space: normal;
}
</style>