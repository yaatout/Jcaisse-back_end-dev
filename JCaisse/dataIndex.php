<?php
/*
Résumé :
Commentaire :
Version : 2.0
see also :
Auteur : Ibrahima DIOP; ,EL hadji mamadou korka
Date de création : 20/03/2016
Date derni�re modification : 20/04/2016; 15-04-2018
*/
session_start();
//echo  $_SESSION['etiquette'];  

require('connection.php');

require('declarationVariables.php');

?>
<?php require('entetehtml.php'); ?>
<body>
<?php 
  require('header.php');
?>
<div class="container" align="center">
  <h2>Working with jQuery DataTables server-side processing using PHP and MySQL</h2>
  <div class="table-responsive">
  <table id="dataTable-example" class="display tabDesign" class="tableau3" align="left" border="1">
    <thead>
      <tr>
          <th>Ordre</th>
          <th>Reference</th>
          <th>Categorie</th>
          <th>Forme</th>
          <th>Tableau</th>
          <th>Prix Session</th>
          <th>Prix Public</th>
          <th>Operations</th>
      </tr>
    </thead>
  </table>
  </div>
  <script type="text/javascript">
  $(document).ready(function() {
      $('#dataTable-example').dataTable({
        "bProcessing": true,
        "sAjaxSource": "dataFetch.php",
        "aoColumns": [
              { mData: '0' } ,
              { mData: '1' },
              { mData: '2' },
              { mData: '3' },
              { mData: '4' },
              { mData: '5' },
              { mData: '6' },
              { mData: '7' },
            ],
           
      });  
  });
</script>
</div>

<div id="recordModal" class="modal fade">
	<div class="modal-dialog">
		<form method="post" id="recordForm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" 
data-dismiss="modal">×</button>
					<h4 class="modal-title"><i 
class="fa fa-plus"></i> Add Record</h4>
				</div>
				<div class="modal-body">
					<div class="form-group"
						<label for="name" class="control-label">Name</label>
						<input type="text" class="form-control" 
id="name" name="name" placeholder="Name" required>			
					</div>
					<div class="form-group">
						<label for="age" class="control-label">Age</label>							
						<input type="number" class="form-control" 
id="age" name="age" placeholder="Age">							
					</div>	   	
					<div class="form-group">
						<label for="lastname" class="control-label">Skills</label>							
						<input type="text" class="form-control"  
id="skills" name="skills" placeholder="Skills" required>							
					</div>	 
					<div class="form-group">
						<label for="address" class="control-label">Address</label>							
						<textarea class="form-control" 
rows="5" id="address" name="address"></textarea>							
					</div>
					<div class="form-group">
						<label for="lastname" class="control-label">Designation</label>							
						<input type="text" class="form-control" 
id="designation" name="designation" placeholder="Designation">			
					</div>						
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id" id="id" />
					<input type="hidden" name="action" id="action" value="" />
					<input type="submit" name="save" id="save" 
class="btn btn-info" value="Save" />
					<button type="button" class="btn btn-default" 
data-dismiss="modal">Close</button>
				</div>
			</div>
		</form>
	</div>
</div>

</body>

</html>




