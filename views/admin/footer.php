<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>


<?php 
if(isset($show_data_table))
{ ?>
<script type="text/javascript">
	$(document).ready(function () {
    var table = $('#list_users').DataTable( {
        lengthChange: false,
        buttons: [ 'excel', 'pdf']
    } );

    var list_sections = $('#list_sections').DataTable( {
        lengthChange: false,
        buttons: [ 'excel', 'pdf']
    } );

    var list_questions = $('#list_questions').DataTable( {
        lengthChange: false,
        order: [],
        buttons: [ 'excel', 'pdf']
    } );

    var list_applications = $('#list_applications').DataTable( {
        lengthChange: false,
        order: [],
        <?php
        $search = $this->input->get('search');
        if($search != "")
        {
            echo '"oSearch": {"sSearch": "';echo $search;echo '"},';
        }
         ?>
        
        
        buttons: [ 'excel', 'pdf']
    } );
 
    table.buttons().container()
        .appendTo( '#list_users_wrapper .col-md-6:eq(0)' );
    list_sections.buttons().container()
        .appendTo( '#list_sections_wrapper .col-md-6:eq(0)' );
    list_questions.buttons().container()
        .appendTo( '#list_questions_wrapper .col-md-6:eq(0)' );
    list_applications.buttons().container()
        .appendTo( '#list_applications_wrapper .col-md-6:eq(0)' );
});
</script>
<script src="<?php echo base_url('assets/js/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatables/dataTables.bootstrap5.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/js/datatables/dataTables.buttons.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/js/datatables/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatables/jszip.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatables/pdfmake.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatables/vfs_fonts.js'); ?>"></script>



	

<?php }

?>

</body>

</html>