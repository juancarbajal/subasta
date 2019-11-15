ALTER procedure [dbo].[KO_SP_AVISO_COMPRADORES_SEL]( 
	@IDAVISO BIGINT,
	@IDUSUARIO BIGINT,
	@FILTRO INT
	
) 
/**************************************************************** 
*Descripcion:	procedimiento de los compradores de un aviso pendientes. 
*Fecha Crea:	16/03/2010   
*Fecha Mod:     16/03/2010 (Nazart Jara)
*Parám. de entrada: "@IDAVISO": aviso a consultar. 
					"@IDUSUARIO" usuario logueado: 
						
*Autor: Nazart Jara 
*Versión: 1.0 (Kotear)   
*****************************************************************/ 

/*
EXEC KO_SP_AVISO_COMPRADORES_SEL 124528, 157246, 0
*/
AS 
SET NOCOUNT ON 
BEGIN	  
DECLARE @SQL NVARCHAR(MAX)='  select distinct usr.APEL, usr.NOM,usr.EMAIL,usrp.APODO,usr.FONO1_ANEXO,usr.FONO1, avi.stock,avi.visitas,
trs.ID_TRANSACCION,trs.REGLA_VENDEDOR, convert(date,trs.FEC_REG) FECR, 
(10-(SELECT DATEDIFF(DAY,trs.FEC_REG,GETDATE()))) AS DIAS_CALIFIC 
from KO_AVISO  avi inner join KO_OFERTA ofe on ofe.ID_AVISO=avi.ID_AVISO 
inner join KO_TRANSACCION trs on trs.ID_OFERTA=ofe.ID_OFERTA 
inner join KO_USUARIO usr on usr.ID_USR=ofe.ID_USR 
inner join KO_USUARIO_PORTAL usrp on usrp.ID_USR=usr.ID_USR 
left join KO_CALIFICACION cal on cal.ID_TRANSACCION=trs.ID_TRANSACCION  
where avi.ID_AVISO='+convert(VARCHAR,@IDAVISO)+'  and avi.ID_USR='+convert(VARCHAR,@IDUSUARIO) 
 
IF @FILTRO=0 BEGIN 
set @SQL=@SQL 
END 
 
IF @FILTRO=1 BEGIN 
set @SQL=@SQL+'and DATEDIFF(DAY,ofe.FEC_REG,GETDATE())<=7 ' 
END 
 
IF @FILTRO=2 BEGIN 
set @SQL=@SQL+'and DATEDIFF(DAY,ofe.FEC_REG,GETDATE())<=30 ' 
END 
 
IF @FILTRO=3 BEGIN 
set @SQL=@SQL+'and trs.REGLA_VENDEDOR is null' 
END 
 
IF @FILTRO=4 BEGIN 
set @SQL=@SQL+'and trs.REGLA_VENDEDOR is not null' 
END 
 
EXEC SP_EXECUTESQL @SQL  
 
END
GO






ALTER PROCEDURE KO_SP_HISTORIAL_COMPRAS_QRY	 
	  @K_ID_USR BIGINT
	 ,@K_ID_CATEGORIA INT
	 ,@K_ID_ESTADO_COMPRA TINYINT
	 ,@FILTRO TINYINT
	 
	 AS
/*******************************************************       
*Descripcion: Store Procedure que permite mostrar el historial de compras.    
       
*Fecha Crea: 04/03/2010       
*Fecha Mod:        
*Parametros: @K_ID_USR: Indica el usuario por el cual se filtraran las compras.  
			 @K_ID_CATEGORIA5 la categoria de nivel1 por la cual se desean filtrar las compras, en caso no se desee especificar un categoriqa este valor debe mandarse con (0) 
			 @K_ID_ESTADO_COMPRA5 el estado de la transaccion por la cual se desea filtrar 
			 @FILTRO: 
					(0)Muestra todas las compras del usuario indicado en el parametro. 
					(1)Muestra todas las compras del usuario indicado en el parametro que no han sido calificadas 
					(2)Muestra todas las compras del usuario indicado en el parametro que  han sido calificadas 
					(3)Muestra todas las compras del usuario indicado que ha sido registradas en la última semana 
					(4)Muestra todas las compras del usuario indicado que ha sido registradas en la último mes 
*Autor: Laura Mariños    
*Autor 2: Brady Lopez (modificado el 12/08/2010 en El Comercio
*Versión: 1.0 (Kotear)       
***************************************/
/*
EXEC KO_SP_HISTORIAL_COMPRAS_QRY 4280,237,0,0
*/
SET NOCOUNT ON 
BEGIN

	 SELECT distinct 
	 		AG.L1 ID_CATEGORIA_PADRE,
			AG.L1_NOM CATEGORIA_PADRE,
			AG.ID_CATEGORIA,  
		    CASE WHEN AG.ID_CATEGORIA=L1 THEN AG.L1_NOM
		    WHEN AG.ID_CATEGORIA=L2 THEN AG.L2_NOM
		    WHEN AG.ID_CATEGORIA=L3 THEN AG.L3_NOM
		    WHEN AG.ID_CATEGORIA=L4 THEN AG.L4_NOM END CATEGORIA,
			DBO.KO_FN_RETURN_RUTA_IMAGEN(F.NOM,AV.ID_USR,AV.IMG_DEF) RUTA_IMAGEN,
			AV.ID_AVISO,
			av.tit TITULO,
			AV.ID_USR, 		
			US.EMAIL, 
			US.FONO1, 
			US.FONO1_ANEXO, 
			US.FONO2,
			UP.APODO, 
			OFE.ID_OFERTA,
			--OFE.FEC_REG FechaOferta, 
			CONVERT(VARCHAR, OFE.FEC_REG ,103) + ' ' + SUBSTRING(CONVERT(VARCHAR, OFE.FEC_REG ,108),1,5) FechaOferta,
			OFE.PRECIO_BASE,
			OFE.CANT,
			TRA.ID_TRANSACCION,
			TRA.REGLA_COMPRADOR,
			--TRA.FEC_REG FechaTransac,
			CONVERT(VARCHAR, TRA.FEC_REG  ,103) + ' ' + SUBSTRING(CONVERT(VARCHAR, TRA.FEC_REG ,108),1,5) FechaTransac,
			--CASE WHEN CAL.ID_CALIFICACION IS NULL AND DATEDIFF(DAY,OFE.FEC_REG,GETDATE())>3 THEN 1 
			--ELSE 0 END CALIFICACION,
			DATEDIFF(DAY,OFE.FEC_REG,GETDATE()) AS DIAS,
			
			CASE  WHEN DATEDIFF(DAY,OFE.FEC_REG,GETDATE())>10 
				THEN case when TRA.REGLA_COMPRADOR is null
					then 2
					else 1
					end
				else case WHEN TRA.REGLA_COMPRADOR IS NULL-- or TRA.REGLA_COMPRADOR<1   
            			THEN 0 
            			ELSE 1 
            			END 
            		end CALIFICACION,
			DBO.KO_FN_RETURN_CANT_IDCATEGORIA_COMPRAS (OFE.ID_USR,AG.ID_CATEGORIA) CANT_VENDIDAS,
			REP.PUNTAJE,
			TP.ICON
			
		
		FROM 
			dbo.KO_OFERTA OFE
			INNER JOIN dbo.KO_AVISO AV ON AV.ID_AVISO = OFE.ID_AVISO
			LEFT JOIN KO_FOTO F ON F.ID_AVISO=AV.ID_AVISO AND F.PRIO=1 
			INNER JOIN dbo.KO_USUARIO_PORTAL UP ON UP.ID_USR=AV.ID_USR
			INNER JOIN dbo.KO_USUARIO US ON US.ID_USR=AV.ID_USR
			INNER JOIN dbo.KO_TRANSACCION TRA ON OFE.ID_OFERTA = TRA.ID_OFERTA 
			INNER JOIN dbo.KO_AVISO_CATEGORIA  AVC ON AV.ID_AVISO= AVC.ID_AVISO 
			INNER JOIN dbo.KO_AGRUPADOR AG ON AG.ID_CATEGORIA=AVC.ID_CATEGORIA 
			LEFT JOIN dbo.KO_CALIFICACION CAL ON CAL.ID_TRANSACCION=TRA.ID_TRANSACCION
			LEFT JOIN dbo.KO_REPUTACION REP ON REP.ID_USR=AV.ID_USR
			INNER JOIN dbo.ko_tipo_usuario TP ON UP.ID_TIPO_USUARIO=TP.ID_TIPO_USUARIO
			
		WHERE
			OFE.ID_USR=@K_ID_USR
			and 
			case when @K_ID_CATEGORIA=0
				then 
					case @FILTRO
						when 0 then 1
						when 1 then case when DATEDIFF(DAY,OFE.FEC_REG,GETDATE())<10 AND TRA.REGLA_COMPRADOR IS NULL-- AND DATEDIFF(DAY,TRA.FEC_REG,GETDATE())<10
								then 1
								else 0
								end
						when 2 then case when TRA.REGLA_COMPRADOR IS NOT NULL-- AND DATEDIFF(DAY,TRA.FEC_REG,GETDATE())<10
								then 1
								else 0
								end
						-- fecha
						when 3 then case when DATEDIFF(DAY,OFE.FEC_REG,GETDATE())<=7
								then 1
								else 0
								end
						-- fecha
						when 4 then case when DATEDIFF(DAY,OFE.FEC_REG,GETDATE())<=30
								then 1
								else 0
								end								
						when 5 then case when DATEDIFF(DAY,OFE.FEC_REG,GETDATE())>10 AND TRA.REGLA_COMPRADOR IS NULL
								then 1
								else 0
								end
					end					
				else 
					case when AG.L1=@K_ID_CATEGORIA
						then 1
						else 0
						end
				end = 1
			and				
			case when @K_ID_ESTADO_COMPRA=0
				then 1
				else case when TRA.ID_ESTADO_TRANSACCION=@K_ID_ESTADO_COMPRA
					then 1
					else 0
					end
				end = 1
		ORDER BY AG.L1_NOM,AG.L1
	END

GO



ALTER FUNCTION [dbo].[KO_FN_CANT_PREG_SIN_CONSTESTAR_USR]
(
@K_ID_USR BIGINT)
RETURNS INT
AS
/*AUTOR: Brady lopez
*FECHA CREACION: 18/03/2010
*FECHA MOD:
*DESCRIPCION: 
Obtiene el numero de preguntas recibidas sin contestar de un usuario en particular como vendedor.
solo se contabilizan las preguntas recibidas sin contestar de avisos activos.
*PARAMETROS:
		@K_ID_USR: Es el identificador del usuario por el cual se hará el filtro.
*/
/*
select  dbo.KO_FN_CANT_PREG_SIN_CONSTESTAR_USR ('157246')
*/
BEGIN
DECLARE 
@V_CANT_COMP  INT 
,@V_CANT_VEND INT

SET @V_CANT_VEND=(
	SELECT COUNT(1)
	from KO_AVISO KA   
	  inner join KO_MENSAJE KM ON KM.ID_REGISTRO=KA.ID_AVISO AND KM.ID_TABLA_MENSAJE=1
	  inner join KO_DETALLE_MENSAJE AS KDM ON KDM.ID_MENSAJE=KM.ID_MENSAJE AND KDM.ID_TIPO_MENSAJE=1  
	  left join KO_DETALLE_MENSAJE KDM2 ON KDM2.ID_MENSAJE=KDM.ID_MENSAJE AND KDM2.ID_TIPO_MENSAJE=2
	  inner join KO_USUARIO_PORTAL KUP ON KUP.ID_USR=KDM.ID_USR   
	where KA.ID_USR=@K_ID_USR AND KDM.ID_TIPO_MENSAJE =1 AND KDM2.ID_DETALLE_MENSAJE IS NULL
		AND KA.STOCK>0 AND KA.EST>=1 AND KA.EST<=2 AND KA.ACTIVO=1 AND KA.FLAG_MODERACION=0 AND KA.FEC_FIN<=GETDATE()
	)
	RETURN  @V_CANT_VEND			
 END

GO




ALTER procedure [dbo].[KO_SP_AVISO_UPD]
(
 @K_ID_AVISO as bigint
,@K_ID_TIPO_PRODUCTO int
,@K_TIT varchar(100)
,@K_SUBTIT varchar(100)
,@K_TAG varchar(255)
,@K_STOCK int
,@K_PRECIO decimal
,@K_HTML nvarchar(max)
,@K_IMG_DEF varchar(50) ='none.gif'
,@K_EST int
,@K_URL varchar(200)
,@K_ID_TIPO_AVISO int
,@K_ID_DURACION int
,@K_ID_REPUBLICACION int
,@K_ID_MONEDA int
,@K_ID_USR bigint
,@K_VISITAS int
,@K_ID_UBIGEO INT
,@K_ID_CATEGORIA VARCHAR(256)	--TRAMA SEPARADA EN COMAS
,@K_ID_MEDIO_PAGO VARCHAR(256)	--TRAMA SEPARADA EN COMAS
,@K_FLAG_MODERACION SMALLINT
) 
as
/***************************************
*Descripcion: Store Procedure que permite modificar un aviso
*Fecha Crea: 18/02/2010    
*Fecha Mod:     
*Parametros: ->(lo necesario para la tabla)
		
*Autor: Cesar Huerta  
*Versión: 1.0 (Kotear)   
***************************************/
/*
/*
EXECUTE KO_SP_AVISO_USR_SEL 12446,94
EXEC [KO_SP_AVISO_UPD] 
	'12446',
	'1', 
	'Cargador Para Automovil Marca Palm',
	'',
	'tag', --tag
	'30',
	'61.00',
	'html',
	'',
	'1',
	'url', --url
	'', --tipo publicacion, aqui no se envia nada
	'9',
	'',
	'1',
	'94', --id_usr
	'100',
	'15',
	'237,439,1482,1886', --categoria
	'1,3,5,6', --medio de pago
	'0'

		select * from ko_aviso where id_aviso=12446
*/

*/
set nocount on
BEGIN

if @K_ID_REPUBLICACION=''
set @K_ID_REPUBLICACION=null
	
	DECLARE @V_ID_AVISO			BIGINT,
			@V_EST				INT,
			@V_ID_DURACION		INT,
			@V_VALOR_DURACION	VARCHAR(10),
			@V_FLAG				INT,
			@V_FINAL			INT,
			@V_ID_TIPO_USUARIO	INT,
			@V_FEC_PUB			datetime,
			@V_FEC_FIN			datetime,
			@V_MSG_ERROR		varchar(max),
			@V_TIPO_AVISO		INT			
								
	SET @V_FLAG=0
	SET @V_FINAL=0
	SELECT @V_TIPO_AVISO=KA.ID_TIPO_AVISO,@V_ID_AVISO=KA.ID_AVISO,@V_EST=KA.EST,@V_ID_DURACION=KA.ID_DURACION,
		@V_ID_TIPO_USUARIO=KTU.ID_TIPO_USUARIO,@V_FEC_FIN=KA.FEC_FIN, @V_FEC_PUB=KA.FEC_PUB
	FROM KO_AVISO KA
	INNER JOIN KO_USUARIO_PORTAL KUP ON KUP.ID_USR=KA.ID_USR
	INNER JOIN KO_TIPO_USUARIO KTU ON KTU.ID_TIPO_USUARIO=KUP.ID_TIPO_USUARIO
	WHERE ID_AVISO=@K_ID_AVISO
	
	IF(@V_ID_AVISO IS NOT NULL)
		BEGIN
			--Modificar el Tipo de Aviso
			IF(NOT EXISTS(SELECT KA.ID_AVISO FROM KO_AVISO KA INNER JOIN KO_OFERTA KO ON KO.ID_AVISO=KA.ID_AVISO WHERE KA.ID_AVISO=@K_ID_AVISO and KO.EST=1 ))
			SELECT * FROM KO_ESTADO_OFERTA
				BEGIN
					SET @V_FLAG=1
					SET @K_ID_TIPO_AVISO=@V_TIPO_AVISO
				END
			ELSE 
				BEGIN
					SET @V_FLAG=0
					SET @K_ID_TIPO_AVISO=@V_TIPO_AVISO
				END			

			--SELECT * FROM KO_TIPO_AVISO WHERE ID_TIPO_AVISO=@K_ID_TIPO_AVISO
			--Modificar el Estado
			SELECT * FROM KO_ESTADO_AVISO 
			IF(@V_EST NOT IN (13,3,6,7,12,14,15))
				BEGIN
					SET @V_FINAL=1
					--IF(((NOT (@K_EST=2 AND @V_EST=1)) OR (@V_EST=3)) OR (@K_EST=4 AND @V_EST=1) OR (@K_EST=4 AND @V_EST=2) AND (@K_EST!=3)) --VALIDA QUE CUMPLA LOS ESTADOS
					--	SET @V_FINAL=1
					--ELSE IF((@K_EST=1 AND @V_EST=2) AND @V_ID_TIPO_USUARIO=2)	--SE VALIDA QUE SEA GRAN VENDEDOR
					--	SET @V_FINAL=1
					--ELSE IF((@K_EST=1 AND @V_EST=4) OR (@K_EST=2 AND @V_EST=4) AND (@V_FLAG=1)) --VALIDA QUE NO TENGA MOVIMIENTO
					--	SET @V_FINAL=1
					--ELSE
					--	SET @V_FINAL=1
					--Verificamos en caso pase en nulo el ubigeo
					IF @K_ID_UBIGEO is NULL
						SET @K_ID_UBIGEO=0
					
					--VALIDANDO LA DURACIÓN
--					IF(@K_ID_DURACION=@V_ID_DURACION)
--						BEGIN 
							--Verificamos si no se ha registrado la fecha de publicacion y por ende la finalizacion
							
							IF(@V_EST IN (5,4,16))
								BEGIN
								SET @V_VALOR_DURACION=(
									select kd.des 
									from ko_duracion kd 
										inner join KO_TIPO_AVISO_DURACION ktad on 
										ktad.ID_DURACION=kd.ID_DURACION and 
										ktad.id_tipo_aviso=@K_ID_TIPO_AVISO
									where ktad.ID_DURACION=@K_ID_DURACION
									)
								SET @V_FEC_PUB=GETDATE()
								--Realizamos el calculo de la Fecha Final de publicacio, solo sucede cuando no tiene FEC_PUB
								SET @V_FEC_FIN=(DATEADD(DAY,CONVERT(INT,@V_VALOR_DURACION),GETDATE()))
								END
								
							IF(@V_EST IN (5,4,16,11))BEGIN
							IF(NOT EXISTS(select * from KO_AVISO_DESTAQUE where ID_AVISO=@K_ID_AVISO and ACTIVO=1 and FLAG_CIERRE=0))BEGIN
								set @K_EST =1 
								END
								END
								
								
							IF(@V_FINAL=1)
								BEGIN
									UPDATE KO_AVISO 
									SET
									 ID_TIPO_PRODUCTO=@K_ID_TIPO_PRODUCTO
									,TIT=@K_TIT 
									,SUBTIT=@K_SUBTIT 
									,TAG=@K_TAG 
									,STOCK=@K_STOCK 
									,FEC_ULT_MOD=getdate()
									,FEC_PUB=@V_FEC_PUB
									,FEC_FIN=@V_FEC_FIN
									,PRECIO=@K_PRECIO 
									,HTML=@K_HTML 
									,IMG_DEF='none.gif'--@K_IMG_DEF 
									,EST=@K_EST 
									,URL=@K_URL 
									,ID_TIPO_AVISO=@K_ID_TIPO_AVISO 
									,ID_DURACION=@K_ID_DURACION 
									,ID_REPUBLICACION=@K_ID_REPUBLICACION 
									,ID_MONEDA=@K_ID_MONEDA 
									,ID_USR=@K_ID_USR 
									,VISITAS=@K_VISITAS 
									,ID_UBIGEO=@K_ID_UBIGEO 
									,FLAG_MODERACION=@K_FLAG_MODERACION
									where ID_AVISO=@K_ID_AVISO
									
									--GENERA TAGS
									exec dbo.KO_SP_AVISO_TAG_BUS @V_ID_AVISO,@K_TAG,1
									
									--UPDATE EN AVISO CATEGORIA
									EXEC dbo.KO_SP_AVISO_CATEGORIA_UPD @K_ID_AVISO,@K_ID_CATEGORIA
									
									--UPDATE EN AVISO MEDIO PAGO
									EXEC dbo.KO_SP_AVISO_MEDIO_PAGO_UPD @K_ID_AVISO,@K_ID_MEDIO_PAGO
									
									
									SELECT 0 AS ERROR,'ACTUALIZACION SATISFACTORIA' AS MSJ
								END
							ELSE
								SELECT 1 AS ERROR,'NO SE PUEDE MODIFICAR POR EL ESTADO' AS MSJ
						END
		--			ELSE
		--				SELECT 1 AS ERROR,'LA DURACIÓN NO PUEDE MODIFICARSE' AS MSJ
												
		--		END										
		END
END
GO


ALTER PROCEDURE [dbo].[KO_SP_AVISO_ULTIMAS_COMPRAS] 
@K_NUM_REG INT 
 
AS 
SET NOCOUNT ON 
/*******************************************************   
*Descripcion: procedimiento que devuelve las ultimas compras cerradas en el portal.
Se debe visualizar solo los avisos que esten activos y no pertenezcan a categoria adulto.
*Fecha Crea: 08/02/2010   
*Fecha Mod:    
*Parametros:@NUM_REG: numero de registros que se va a devolver 
*Autor: Manuel Francia 
*Versión: 1.0 (Kotear)   
***************************************/  
/*
EXEC KO_SP_AVISO_ULTIMAS_COMPRAS 4
*/
 SELECT  
 DISTINCT 
 TOP (@K_NUM_REG) 
 A.ID_AVISO,  
 A.TIT,  
 A.PRECIO,  
 M.SIMB,  
 A.ID_USR, 
 O.CANT,  
 CT.FLAG_CIERRE,  
 CT.COMISION, 
 O.PRECIO_BASE, 
 DBO.KO_FN_RETURN_RUTA_IMAGEN(F.NOM, A.ID_USR, A.IMG_DEF) RUTA_IMAGEN,
 CT.FEC_TRANSACCION,
 KC.ADULTO
 FROM KO_CIERRE_TRANSACCION CT 
    INNER JOIN KO_TRANSACCION T ON CT.ID_TRANSACCION= T.ID_TRANSACCION AND T.ID_ESTADO_TRANSACCION=1
    INNER JOIN KO_OFERTA O ON T.ID_OFERTA= O.ID_OFERTA --and O.EST=1 
    INNER JOIN KO_AVISO A ON O.ID_AVISO= A.ID_AVISO and A.FLAG_MODERACION=0 AND A.STOCK>0 AND A.EST>=1 AND A.EST<=2 AND A.FEC_FIN>GETDATE() AND A.ACTIVO=1
    inner join KO_AVISO_CATEGORIA KAC ON KAC.ID_AVISO=A.ID_AVISO
    inner join KO_CATEGORIA KC ON KC.ID_CATEGORIA=KAC.ID_CATEGORIA and KC.ADULTO=0
    INNER JOIN KO_MONEDA M ON A.ID_MONEDA= M.ID_MONEDA 
    LEFT JOIN KO_FOTO F ON F.ID_AVISO = A.ID_AVISO and F.PRIO=1
    --WHERE CT.FLAG_CIERRE=0
 ORDER BY CT.FEC_TRANSACCION DESC
GO
