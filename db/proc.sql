﻿DROP PROCEDURE IF EXISTS `sp_change_info`;
DROP PROCEDURE IF EXISTS `sp_create_order`;
DROP PROCEDURE IF EXISTS `sp_create_users`;
DROP PROCEDURE IF EXISTS `sp_create_product`;
DELIMITER $$

/*Thay đổi thông tin info của usersm xóa củ và làm lại cái mới picture, userpicture*/
CREATE PROCEDURE `sp_change_info` (
	IN `user_id` INT, 
	IN `user_password` VARCHAR(191), 
	IN `user_description` VARCHAR(191), 
	IN `picture_url` VARCHAR(191))  
sp_change_info:BEGIN
	IF NOT EXISTS(SELECT * FROM users WHERE users.id = `user_id`) THEN
		LEAVE sp_change_info;
	END IF;
	Update users SET users.Password = `user_password`, users.Description =  `user_description` WHERE users.id = `user_id`;
    	SET @PICTUREID := (SELECT ID_Picture FROM userspicture WHERE ID_Users = `user_id`);
	DELETE FROM userspicture WHERE ID_Users = `user_id`;
	DELETE FROM picture WHERE id = @PICTUREID;
	INSERT INTO picture(Url,IsDelete,created_at) VALUES(`picture_url`,0,CURRENT_TIMESTAMP);
	SET @PICTUREID := (SELECT MAX(id) FROM picture);
	INSERT INTO userspicture(ID_Users,ID_Picture,IsDelete,created_at) VALUES(`user_id`,@PICTUREID,0,CURRENT_TIMESTAMP);
END$$

/*Tạo hóa đơn, tự động cập nhật danh sách còn trong cart trong OrderProduct của user*/
CREATE PROCEDURE `sp_create_order` (
	IN `vDescription` varchar(191), 
	IN `vID_Promotion` int(10), 
	IN `vID_DeliveryPlace` int(10), 
	IN `vID_User` int(10),
	IN `vConfirmDate` date,
	IN `vIsPaied` tinyint(1),
	IN `vIsDelivered` tinyint(1),
	IN `vIsDelete` tinyint(1)
)  
BEGIN
	INSERT INTO `order`(Description,ID_Promotion,ID_DeliveryPlace,ID_User,ConfirmDate,IsPaied,IsDelivered,IsDelete,CreateDate) VALUES(`vDescription`,`vID_Promotion`,`vID_DeliveryPlace`,`vID_User`,`vConfirmDate`,`vIsPaied`,`vIsDelivered`,`vIsDelete`,CURRENT_TIMESTAMP);
	SET @OrID := (SELECT MAX(id) FROM `order`);
	UPDATE `orderproduct` SET IsInCart = 0, ID_Order = @OrID WHERE IsDelete = 0 AND ID_User = `vID_User` AND `IsInCart` = 1;
END$$

/*Tạo user, tự động tạo picture and role of users*/
CREATE PROCEDURE `sp_create_users` (
	IN `vUsername` varchar(191), 
	IN `vPassword` varchar(191), 
	IN `vEmail` varchar(191), 
	IN `vDescription` varchar(191),
	IN `vIsDelete` tinyint(1),
	IN `vID_Role` tinyint(1),
	IN `picture_url` varchar(191)
)  
BEGIN
	INSERT INTO `users`(Username,Password,Email,Description,IsDelete) VALUES(`vUsername`,`vPassword`,`vEmail`,`vDescription`,`vIsDelete`);
	SET @MAXID := (SELECT MAX(id) FROM `users`);
	INSERT INTO picture(Url,IsDelete,created_at) VALUES(`picture_url`,0,CURRENT_TIMESTAMP);
	SET @PICTUREID := (SELECT MAX(id) FROM picture);
	INSERT INTO userspicture(ID_Users,ID_Picture,IsDelete,created_at) VALUES(@MAXID,@PICTUREID,0,CURRENT_TIMESTAMP);
	INSERT INTO `user_role`(`ID_Users`, `ID_Role`, `IsDelete`) VALUES (@MAXID, `vID_Role`, 0);
	SELECT @MAXID as id;
END$$

/*Tạo create product, khởi tạo product nếu không tồn tại Id and tạo picture, size, color by list array*/
CREATE PROCEDURE `sp_create_product` (
	IN `vId` int(10),
	IN `vID_ProductCategory` int(10), 
	IN `vName` varchar(191),
	IN `vDescription` varchar(191),
	IN `vIsDelete` tinyint(1),
	IN `vPrice` varchar(191),
	IN `vDiscount` INT,
	IN `vListColor` varchar(191),
	IN `vListSize` varchar(191),
	IN `vListPicture` varchar(191)
)  
BEGIN
	IF EXISTS (SELECT * FROM `product` WHERE id = `vId`) THEN
		UPDATE `product` SET ID_ProductCategory = `vID_ProductCategory`, Name = `vName`, Description=`vDescription`, IsDelete =`vIsDelete` WHERE id = `vId`;
		SET @MAXID := `vId`;
	ELSE
		INSERT INTO `product`(`ID_ProductCategory`, `Name`, `Description`, `IsDelete`) VALUES (`vID_ProductCategory`, `vName`, `vDescription`, `vIsDelete`);
		SET @MAXID := (SELECT MAX(id) FROM `product`);
	END IF;
	INSERT INTO `productprice`(`Price`, `StartDate`, `EndDate`, `IsDelete`, `ID_Product`, `Discount`, `created_at`) VALUES (`vPrice`, CURRENT_TIMESTAMP, NULL, 0, @MAXID, vDiscount, CURRENT_TIMESTAMP);
	set @valueColor :=`vListColor`; 
	WHILE (LOCATE(',', @valueColor) > 0) 
		DO
		SET @V_DESIGNATION = SUBSTRING(@valueColor,1, LOCATE(',',@valueColor)-1); 
		SET @valueColor = SUBSTRING(@valueColor, LOCATE(',',@valueColor) + 1); 
		INSERT INTO `productcolor`(`ID_Product`, `ID_Color`, `IsDelete`) VALUES (@MAXID, @V_DESIGNATION, 0);
	END WHILE;
	set @valueSize :=`vListSize`; 
	WHILE (LOCATE(',', @valueSize) > 0) 
		DO
		SET @V_DESIGNATION = SUBSTRING(@valueSize,1, LOCATE(',',@valueSize)-1); 
		SET @valueSize = SUBSTRING(@valueSize, LOCATE(',',@valueSize) + 1); 
		INSERT INTO `productcolor`(`ID_Product`,`ID_Size`, `IsDelete`) VALUES (@MAXID, @V_DESIGNATION, 0);
	END WHILE;
	set @valuePicture :=`vListPicture`; 
	WHILE (LOCATE(',', @valuePicture) > 0) 
		DO
		SET @V_DESIGNATION = SUBSTRING(@valuePicture,1, LOCATE(',',@valuePicture)-1); 
		SET @valuePicture = SUBSTRING(@valuePicture, LOCATE(',',@valuePicture) + 1); 
		INSERT INTO picture(Url,IsDelete,created_at) VALUES(@V_DESIGNATION,0,CURRENT_TIMESTAMP);
		SET @PICTUREID := (SELECT MAX(id) FROM picture);
		INSERT INTO `productprice`(`ID_Product`,`ID_Picture`,`IsDelete`) VALUES(@MAXID,@PICTUREID,0,CURRENT_TIMESTAMP);
	END WHILE;
	select @MAXID as id;
END$$


/* 
1/Lấy danh sách sản phâm trong giỏ hàng và giá của sản phẩm đó hiện tại
2/Lấy danh sách sản phâm trong hóa đơn và giá sản phẩm tại hóa đơn
*/



DELIMITER ;