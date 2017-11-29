﻿<!-- name,id color rdColor_idProduct__idColor -->
<!-- name,id color rdColor_idProduct__Sizeid -->

<?php $__env->startSection('title'); ?> Quản lý giỏ hàng <?php $__env->stopSection(); ?>
<?php $__env->startSection('link'); ?>
##parent-placeholder-4f0aa52d656a3d75867f784b7e9c5d23bf1321c0##
<link rel="stylesheet" type="text/css" href="<?php echo url('public/css/Cart/Cart.css'); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3>Giỏ hàng <span>(<span id="countProduct"><?php echo isset($listCartProduct) ?  count($listCartProduct) : 0?></span> sản phẩm)</span></h3>
		</div>
	</div>
</div>

<div class="container">
	<div class="Shoppingcart">
		<?php if(isset($listCartProduct) && count($listCartProduct)>0){
			$pricefinal = 0;
			$sumPrice = 0;
			foreach ($listCartProduct as $valueProductOrder) {
				$countProduct = 0;?>
				<?php 
				$productItem = $valueProductOrder->Product()->get()[0];
			// kiêm tra tồn tại
				if(!isset($productItem)){
					break;
				}
				?>
				<!-- product item -->
				<div class="cart-product" data-id ="<?php echo $valueProductOrder->id ?>">
					<div class="row">
						<!-- image products -->
						<div class="col-md-3 product_image">
							<img class="img-responsive thumbnail" src="<?php echo url('public/images'); ?>/<?php echo $productItem->Pictures()->get()[0]->Url ?>">
						</div>
						<div class="col-md-4 name">
							<!-- name -->
							<h3><a href=""><?php echo $productItem->Name; ?></a><br></h3>
							<!-- color -->
							<?php 
							// lấy danh sách không bị delete
							$colorProduct = $productItem->Colors()->where([['ProductColor.IsDelete','=',false]])->get();
							if(count($colorProduct)>0){?>
							<div class="color">
								<p class="product-field">Màu sắc: </p>
								<span class="radio-inline">
									<!-- lấy danh sách màu sắt -->
									<?php foreach ($colorProduct as $valueColor) {?>
									<input id="rdColor_<?php echo $productItem->id; ?>_<?php echo $valueColor->id; ?>" class="radio-infomation" type="radio" name="rdColor_<?php echo $productItem->id; ?>" <?php if($valueColor->id==$valueProductOrder->ID_Color){ echo "checked";} ?> data-color="<?php echo $valueColor->id; ?>" >
									<label for="rdColor_<?php echo $productItem->id; ?>_<?php echo $valueColor->id; ?>">
										<span style="background: #<?php echo $valueColor->Color ?>;"></span>
									</label>
									<?php } ?>
								</span>
							</div>
							<?php } ?>

							<!-- lấy danh sách size không bị delete-->
							<?php $listSize = $productItem->Sizes()->where([['ProductSize.IsDelete','=',false]])->get(); 
							if(count($listSize)>0){?>
							<div class="Size">
								<p class="product-field">Kích thước: </p>
								<!-- danh sách kích thứóc -->
								<span class="radio-inline">
									<!-- lấy danh kích cở -->
									<?php foreach ($listSize as $valueSize) {?>
									<input id="rdSize_<?php echo $productItem->id; ?>_<?php echo $valueSize->id; ?>" class="radio-infomation" type="radio" name="rdSize_<?php echo $productItem->id; ?>" <?php if($valueSize->id == $valueProductOrder->ID_Size){ echo "checked";} ?>  data-size="<?php echo $valueSize->id; ?>" >
									<label for="rdSize_<?php echo $productItem->id; ?>_<?php echo $valueSize->id; ?>">
										<!-- value size -->
										<span style="background: transparent;"><?php echo $valueSize->Sizes ?></span>
									</label>
									<?php } ?>
								</span>
							</div>
							<br>
							<?php } ?>
						</div>
						<!-- giá product -->
						<?php
						$price=	$productItem->Prices()->whereNull('endDate')->get(); 
						// tính toán tiền
						if(count($price)>0){
							$pricefinal  =$price[0]['Price'] -( $price[0]['Price'] * ($price[0]['Discount'] /100));
							?>
							<div class="price col-md-2" data-price="<?php echo $pricefinal ?>"  >
								<?php
								echo $pricefinal." VNĐ";
								?>
							</div>
							<?php } ?>
							<!-- số lượng -->
							<div class="col-md-2 Numberofproduct ">
								<input  type="number" name="txtNumberProduct" class="txtNumberProduct" class="form-control" value="<?php 
								$countProduct = $valueProductOrder->Count;
								echo $valueProductOrder->Count; 
								?>" min="1">
							</div>

							<div class="col-md-1 delete">
								<button class="btn btn-default btnCart btn-delete " data-delete="<?php echo $valueProductOrder->id; ?>">
									<span class="glyphicon glyphicon-trash"></span>							 									 	
								</button>
							</div>
						</div> <!-- end row-->

					</div> <!-- end  product-->
					<?php
					$sumPrice+= $pricefinal *$countProduct;
				}
				?>
				<?php 
			}else{
			// do somthings
			} ?>
		</div> 
		<!-- end Shopping-cart -->
	</div>
	<!-- end contAINER -->
	<!--   Đặt hàng -->
	<div class="container">
		<div class="total-prices">
			<div class="row">
				<h3  class="finalTotalPrice pull-right"><?php if(isset($sumPrice) && $sumPrice > 0){ echo $sumPrice;}else{echo '0';} ?> VNĐ</h3>
				<h3 class="pull-right" style="margin-right: 20px;">Tổng tiền:</h3>
			</div>
		</div>
		<div class="row">
			<form enctype="multipart/form-data" action="<?php echo url('/cart/order'); ?>" method="POST" name="frmBuy">
				<?php echo e(csrf_field()); ?>	
				<div class="button-Order">
					<button type="submit" id="btnBuyProduct" class="col-md-4 col-md-push-7 btn btn-danger">Đặt hàng</button>
				</div>
			</form>
		</div>
	</div>
	<?php $__env->stopSection(); ?>
	<!-- my js -->
	<?php $__env->startSection('script'); ?>
	##parent-placeholder-cb5346a081dcf654061b7f897ea14d9b43140712##
	<script type="text/javascript" src="<?php echo url('public/js/Cart/index.js'); ?>"></script>
	<?php $__env->stopSection(); ?>


<?php echo $__env->make('_layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>