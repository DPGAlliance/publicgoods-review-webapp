<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

<?php 
if(isset($show_data_table))
{ ?>
<script type="text/javascript">
	$(document).ready(function () {
    $('#list_directory').DataTable();
});
</script>
<script src="<?php echo base_url('assets/js/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatables/dataTables.bootstrap5.min.js'); ?>"></script>
	

<?php }

?>

</body>

</html>