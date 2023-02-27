

<div class="col-md-10">
	<div class="custom_column_reviewer">
					<div>
 						
 						<a href="<?php echo base_url('/admin/questions') ?>" class="btn
 						<?php
 						if($sub_active_menu == "All Questions List")
 						{
 							echo "btn-primary";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">All Questions List</a>

 						<button type="button" class="btn btn-light btn-sm left_admin_menu" data-bs-toggle="modal" data-bs-target="#addNewQuestionTextModal">
						Add Question (Text)
						</button>

						<button type="button" class="btn btn-light btn-sm left_admin_menu" data-bs-toggle="modal" data-bs-target="#addNewQuestionTextBoxModal">
						Add Question (TextBox)
						</button>

						<button type="button" class="btn btn-light btn-sm left_admin_menu" data-bs-toggle="modal" data-bs-target="#addNewQuestionSingleSelectModal">
						Add Question (Single Select)
						</button>

						<button type="button" class="btn btn-light btn-sm left_admin_menu" data-bs-toggle="modal" data-bs-target="#addNewQuestionMultiSelectModal">
						Add Question (Multi Select)
						</button>

						<?php 
						if($sub_active_menu == "Filtered Data")
 						{
 							echo "<button type='button' class='btn btn-primary btn-sm left_admin_menu'>
						<i class='bi bi-funnel'></i> $filter_text </button>";
 						}

						?>

						

 					</div> <!-- page menu end -->
 					<hr>