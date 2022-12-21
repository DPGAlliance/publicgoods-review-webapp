<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

<?php 
if(uri_string() == 'reviewer/directory')
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