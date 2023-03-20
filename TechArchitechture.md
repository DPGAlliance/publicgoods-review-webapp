<h1 align="center"> 🎯 Tech Architechture </h1> 
This file contains details of the various capabilities and functionalities of the Digital Public Goods Webapp. 
<br><br> All files being referred to here can be found in the Applications > Controllers folder of this repository. <a href="https://github.com/DPGAlliance/publicgoods-review-webapp/tree/main/application/controllers"> Here </a> is a quick link. 
<br>
<h3> Admin </h3> 

The Admin panel allows the administrators of this program to view graphs and statistics regarding all the applications processed. They get to see the application logs filtered by date, application and individual reviewer. They also manage permissions + access for users, and set time limits for reviewers to process each application. It also allows them to edit answers, reset questions, and change the status of all applications. 
<br>
<h3> API  </h3> 

The API file manages the site <a href="api.digitalpublicgoods.net"> api.digitalpublicgoods.net </a> and all related API. 
<br>
<h3> Crons </h3> 

The Crons file automates scheduled tasks. It ensures that applications are marked 'late' automatcially if they have been in a reviewers panel for long, or it will mark an application as 'ineligible' if clarifications are not submitted by the applicant on time. 
<br>
<h3> Expert  </h3> 

The Expert file helps expert reviewers of the DPGA to give their inputs on particular indicators as required by the DPGA reviewer team. They can only see + respond to what is directly assigned to them. 
<br>
<h3> Applicant </h3> 

This file handles all the capabilities of an applicants view of the panel. It allows users to create + submit their application form to be assessed as potential DPGs. 
<br>
<h3> Front Page </h3> 

The file contains the landing page requirements of the webapp such as log in + sign up. 
<br>
<h3> General Public </h3> 


The General Public file hosts the public URLs of all DPGs. <br>
<h3> Reviewers </h3> 

The Reviewers file has capabilities which allow reviewers to assess applications - mark them as pass, fail or pass them for consultation and clarifications. It allows them to view their previous decisions taken on any application as well as monitor the logs of time taken. 
<br>
<h3> Users </h3> 

This file includes functionalities for Email Verification and Forgot Password. 
