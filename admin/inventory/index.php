<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.product-img{
		width:3em;
		height:3em;
		object-fit:cover;
		object-position:center center;
	}
</style>
<div class="card card-outline rounded-0 card-light">
	<div class="card-header">
		<h3 class="card-title">Inventory</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="10%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Image</th>
						<th>Brand</th>
						<th>Name</th>
						<th>Available</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT *, (COALESCE((SELECT SUM(quantity) FROM `stock_list` where product_id = product_list.id and (expiration IS NULL or date(expiration) > '".date("Y-m-d")."') ), 0) - COALESCE((SELECT SUM(quantity) FROM `order_items` where product_id = product_list.id), 0)) as `available` from `product_list` where delete_flag = 0 and `status` = 1 order by `brand` asc, `name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class="text-center">
								<img src="<?= validate_image($row['image_path']) ?>" alt="" class="img-thumbnail p-0 border product-img">
							</td>
							<td class=""><?= $row['brand'] ?></td>
							<td class="">
								<div style="line-height:1em">
									<div><?= $row['name'] ?></div>
									<div><small class="text-muted"><?= $row['dose'] ?></small></div>
								</div>
							</td>
							<td class="text-right"><?= format_num($row['available'],0) ?></td>
							<td align="center">
                                <a class="btn btn-sm btn-flat btn-light bg-gradient-light border" href="./?page=inventory/view_inventory&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
							</td>
						</tr>
						<script>
							
  							var center = document.getElementsByClassName("text-right"); // Get all the quantity cells
  							for (var i = 0; i < center.length; i++) { // Loop through each quantity cell
    						var available = center[i];
							
				
    
    						if (available.innerHTML <= 0) { // If quantity is 0
      						available.style.background = "#ff00009c"; // Set color to red
    						} 
	
							else if (available.innerHTML >=1 && available.innerHTML <=10) { // If quantity is less than or equal to 5
      						available.style.background = "yellow"; // Set color to yellow
							
    						} 
							else { // If quantity is greater than 5
      						available.style.color = "black"; // Set color to black
    						}
  							}

							
							</script>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [2,6] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
</script>