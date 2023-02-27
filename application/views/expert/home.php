    <div class="row">
      <div class="col-md-12">
        <?php 
        echo $this->session->flashdata('message');  
        ?>
         </div>
 
<div class="row">
      <div class="col-md-12">
          <h5>Under Consultation</h5>
      </div>
    </div>

    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered border-secondary solution_table">
          <thead>
            <tr>
              <th scope="col">Application ID</th>
              <th scope="col">Solution Name</th>
               <th scope="col">Expert Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(count($under_consultation_application)> 0)
            {
              foreach ($under_consultation_application as $key => $application) {
                echo "<tr>";
                echo "<td> ";
                $application_id = $application['id'];
                echo anchor(base_url("a/$application_id"), $application_id, array('title' => 'Click here to see application details',
                  'target' => '_blank'));
                
                echo "</td>";
                echo "<td> " .$application['answer']. "</td>";
                

                   echo "<td>";

                   $expert_name = "";
                   $experts_array = array();
                   $experts_total_array = array();
                   $total_asked_experts = array();
                   foreach ($experts_response as $key => $ind_response) {


                     if($ind_response['application_id'] == $application['id'])
                     {
                      
                      $expert_name = $ind_response['fname'];
                      
                      if (!array_key_exists($expert_name,$experts_array))
                        {

                         
                        $experts_array[$expert_name] = 0;
                        $experts_total_array[$expert_name] = 0;
                        }
                      if($ind_response['response'] == '')
                      {
                        $experts_array[$expert_name];
                        $experts_array[$expert_name] = $experts_array[$expert_name]+1;

                      }
                      $experts_total_array[$expert_name] = $experts_total_array[$expert_name]+1;
                     }
                     
                   }


                   foreach ($experts_array as $key => $pending_response) {

                    if($user_details[0]['fname'] == $key)
                    {
                      $total_asked = $experts_total_array[$key];
                      $total_respond = $total_asked-$pending_response;

                      if($pending_response == 0)
                      {
                        $respond_status = "Complete";
                        //echo $key; echo " - Complete"; echo "<br>";
                      }else{
                         $respond_status = "Pending";
                        //echo $key; echo " - Pending ("; echo $pending_response; echo "/"; 
                        //echo $experts_total_array[$key]; echo ")";
                        //echo "<br>";
                      }
                      echo "$key - $respond_status ($total_respond/$total_asked)<br>";
                    }
                   }

                   echo "</td>";


                  echo "<td>";
                  echo anchor("start/inputs/" .$application['id']. "", 'Give Inputs', array('class' => 'btn btn-info btn-sm')); 
                  echo "</td>";

                echo "</tr>";
              }

            }else{
              echo '<tr>
              <td colspan="5" style="color: red;">No Application found in under consultation.</td>
            </tr>';
            }
?>
             </tbody>

        </table>
      </div>
    </div> <!-- table div end -->



    

 
  </div>
</div>




