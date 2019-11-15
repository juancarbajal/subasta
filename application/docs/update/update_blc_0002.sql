/*
exec [KO_SP_BUSQUEDA_FILTRO] -1,'televisor','','-1','','','-1','-1','-1','-1','-1','-1','-1','-1','-1','1','1','70','0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '208', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '10', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '208', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '-1', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '-1', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '2', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '-1', '', 'ALECROX', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '1909', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '237', '', '', '-1', '-1', '-1', '-1', '2', '-1', '1', '-1', '-1', '-1', '133', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '237', '', '', '-1', '-1', '-1', '-1', '2', '-1', '1', '-1', '-1', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '237', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '-1', '132', '30', '0' 
EXECUTE KO_SP_BUSQUEDA_FILTRO '-1', '', '', '237', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '1', '-1', '-1', '-1', '132', '30', '0'

*/
ALTER PROCEDURE [dbo].[KO_SP_BUSQUEDA_FILTRO]
	@K_Cod_Aviso bigint =-1
	,@K_PalabraBuscar varchar(400) =''
	,@K_PalabraExcluida varchar(200) =''
	,@K_IdCategoria int =-1
	,@K_USUARIO varchar(50)=''
	,@K_Apodo varchar(50) =''
	,@K_TIPO_USUARIO INT =-1
	,@K_ID_RANGO_USUARIO INT =-1
	,@K_PRECIO1 DECIMAL =-1
	,@K_PRECIO2 DECIMAL =-1
	,@K_ID_UBIGEO NVARCHAR(50) ='-1'
	,@K_ID_MONEDA INT =-1
	,@K_ID_TIPO_PRODUCTO INT =-1
	,@K_ID_TIPO_AVISO INT =-1
	,@K_ID_MODULO INT =-1
	,@K_ORDEN INT = -1
	,@K_NUM_PAGINA INT =1
	,@K_NUM_REGISTROS_PAGINA INT =30
	,@K_VISUALIZA_ADULTO int =0
AS 
 
SET NOCOUNT ON 
SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED

DECLARE @TABLA as BUSQUEDATIPOTABLE
DECLARE @TABLAFILTRADA as BusquedaTabla
DECLARE @TABLAFILTRADA2 as BusquedaTabla
DECLARE @TABLAFILTROS table (
	ID_AVISO bigint,
	ID_CATEGORIA bigint,
	ADULTO int, 
	ORDENADO varchar(20),
	VISITAS int,
	SAL int) 

if (@K_ID_UBIGEO is not null) or (@K_ID_UBIGEO <> '') set @K_ID_UBIGEO = cast (@K_ID_UBIGEO as varchar(50))

declare 
	@cont int
	,@Maximo int 
	,@Minimo int
	,@TotalRegistros bigint
	,@FechaActual datetime = getdate()
	,@TipoCambio decimal(10,2)
		
set @Maximo = (@K_NUM_PAGINA * @K_NUM_REGISTROS_PAGINA) 
set @Minimo = @Maximo - (@K_NUM_REGISTROS_PAGINA - 1)
select top 1 @TipoCambio = VALOR_CAMB from KO_TIPO_CAMBIO where FEC_FIN is null

set @cont =
		case when @K_Cod_Aviso=-1 
			then 0 else 1
			end
		+
		case when @K_PalabraExcluida='' 
			then 0 else 1
			end
		+
		case when @K_USUARIO=''  
			then 0 else 1
			end
		+
		case when @K_Apodo=''  
			then 0 else 1
			end
		+
		case when @K_TIPO_USUARIO=-1 
			then 0 else 1
			end
		+
		case when @K_ID_RANGO_USUARIO=-1 
			then 0 else 1
			end
		+
		case when (@K_PRECIO1=-1 or  @K_PRECIO2=-1)
			then 0 else 1
			end
		+
		case when @K_ID_UBIGEO='-1'
			then 0 else 1
			end
		+
		case when @K_ID_MONEDA=-1  
			then 0 else 1
			end
		+
		case when @K_ID_TIPO_PRODUCTO=-1 
			then 0 else 1
			end
		+
		case when @K_ID_TIPO_AVISO=-1 
			then 0 else 1
			end
		+
		case when @K_ID_MODULO=-1  
			then 0 else 1
			end

INSERT INTO @TABLA 
	EXEC KO_SP_BUSQUEDA_AVISO_CATEGORIA @K_PalabraBuscar,@K_IdCategoria,@K_VISUALIZA_ADULTO

;
with Busqueda as (
	select 
		ROW_NUMBER () over (order by ORDENADO desc, VISITAS desc) as NroRegistro,
		ID_AVISO,
		ID_CATEGORIA,
		ADULTO,
		1 as registro,
		ORDENADO
	from 
		(
		select 
			TA.ID_AVISO,
			TA.ID_CATEGORIA,
			TA.ADULTO,
			ORDENADO = STR(
				case when @K_ORDEN=-1
					then KD.MONTO else
						case @K_ORDEN 
							WHEN 1 THEN 
								case when KA.ID_MONEDA = 1
									then 99999999 - PRECIO
									else 99999999 - (@TipoCambio * PRECIO)
									end																					
							WHEN 2 THEN
								case when KA.ID_MONEDA = 1
									then PRECIO
									else (@TipoCambio * PRECIO)
									end
							WHEN 3 THEN 99999999 - (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
							WHEN 4 THEN (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
							WHEN 5 THEN 99999999 - VISITAS 
							WHEN 6 THEN VISITAS							
							WHEN 7 THEN 99999999 - cast ( convert( char(8), FEC_FIN, 112 ) As integer )
							WHEN 8 THEN convert( char(8), FEC_FIN, 112)
						END 
				end,10,2 
				)
			,KA.VISITAS
			,sal= 
			(case when @K_Cod_Aviso=-1
			then 0 else 
				case when KA.ID_AVISO=@K_Cod_Aviso 
					then 1 else 0 
				end 
			end 
			+
			case when @K_PalabraExcluida=''
			then 0 else 
				case when CHARINDEX(@K_PalabraExcluida, KA.TIT ) > 0
		
			then 0 else 1 
				end 
			end 
			+
			case when @K_USUARIO=''
			then 0 else 
				case when CHARINDEX(@K_USUARIO, KUP.APODO + ' ,' + KU.NOM + ' ,' + KU.APEL+ ' ,' + cast(KUP.ID_USR as varchar(50))) > 0
					then 1 else 0
				end
			end			
			+
			case when @K_Apodo=''
			then 0 else 
				case when KUP.APODO = @K_Apodo
					then 1 else 0
				end
			end
			+
			case when @K_TIPO_USUARIO=-1
			then 0 else 
				case when KTU.ID_TIPO_USUARIO=@K_TIPO_USUARIO 
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_RANGO_USUARIO=-1
			then 0 else 
				case when KR.PUNTAJE>=@K_ID_RANGO_USUARIO
					then 1 else 0 
				end 
			end 
			+
			case when (@K_PRECIO1=-1 or @K_PRECIO2=-1)
			then 0 else 
				case when KA.ID_MONEDA = 1 					then case when (KA.PRECIO BETWEEN @K_PRECIO1 AND @K_PRECIO2)
						then 1 else 0 
						end
					else
						case when ((@TipoCambio * KA.PRECIO) BETWEEN @K_PRECIO1 AND @K_PRECIO2)
						then 1 else 0
						end
				end 
			end
			+
			case when @K_ID_UBIGEO='-1'
				then 0 else 
				case when CHARINDEX(',' + cast(KUB.ID_UBIGEO as varchar(10)) + ',', ',' + @K_ID_UBIGEO + ',') > 0
					then 1 else 0
				end 
			end 
			+
			case when @K_ID_MONEDA = -1
				then 0 else 
				case when KA.ID_MONEDA = @K_ID_MONEDA 
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_TIPO_PRODUCTO=-1
			then 0 else 
				case when KTP.ID_TIPO_PRODUCTO = @K_ID_TIPO_PRODUCTO 
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_TIPO_AVISO=-1
			then 0 else 
				case when KA.ID_TIPO_AVISO=@K_ID_TIPO_AVISO
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_MODULO=-1
			then 0 else 
				case when KA.ID_TIPO_AVISO=@K_ID_MODULO
					then 1 else 0 
				end 

			end 
)
		from @TABLA TA
		inner join KO_AVISO KA ON KA.ID_AVISO=TA.ID_AVISO AND
			KA.EST>=1 AND KA.EST<=2		AND 
			KA.FLAG_MODERACION=0		AND 
			KA.ACTIVO=1					AND 
			KA.STOCK>0					AND
			KA.FEC_FIN > @FechaActual		
		inner join KO_AVISO_DESTAQUE KAD (NOLOCK) on KAD.ID_AVISO=KA.ID_AVISO and KAD.ACTIVO=1		
		inner join KO_DESTAQUE KD (NOLOCK) on KD.ID_DESTAQUE=KAD.ID_DESTAQUE and KD.ID_TIPO_DESTAQUE=2	
		inner join KO_MONEDA KM (NOLOCK) on KA.ID_MONEDA=KM.ID_MONEDA
		inner join KO_TIPO_PRODUCTO KTP (NOLOCK) on KTP.ID_TIPO_PRODUCTO=KA.ID_TIPO_PRODUCTO
		inner join KO_TIPO_AVISO KTA (NOLOCK) on KTA.ID_TIPO_AVISO=KA.ID_TIPO_AVISO
		inner join KO_USUARIO_PORTAL KUP (NOLOCK) on KUP.ID_USR=KA.ID_USR and ID_ESTADO_USUARIO=2
		inner join KO_USUARIO KU (NOLOCK) on KU.ID_USR=KA.ID_USR		
		inner join KO_REPUTACION KR (NOLOCK) on KR.ID_USR=KA.ID_USR
		inner join KO_TIPO_USUARIO KTU (NOLOCK) on KTU.ID_TIPO_USUARIO=KUP.ID_TIPO_USUARIO		
		inner join KO_UBIGEO KUB (NOLOCK) on KUB.ID_UBIGEO=KA.ID_UBIGEO		
	) D
	where 
		sal = @cont and
		case when @K_VISUALIZA_ADULTO=1
			then 1
			else 
				case when D.ADULTO = 1
					then 0 else 1
				end
		end = 1
	)
	INSERT INTO @TABLAFILTRADA2
	select 
		INDICADOR = NroRegistro,
		BU.ID_AVISO,
		BU.ID_CATEGORIA,
		DESCRIPCION = 'Aviso',
		BU.ADULTO,
		TOTAL = (select max(NroRegistro) from Busqueda),
		ORDENADO
	from Busqueda BU
	
		
	insert into @TABLAFILTRADA
	select 
		INDICADOR = 1,
		ID_AVISO,
		ID_CATEGORIA,
		DESCRIPCION = 'Aviso',
		ADULTO,
		TOTAL,
		ORDENADO
	from @TABLAFILTRADA2 as TA
	where  
		INDICADOR BETWEEN @Minimo AND @Maximo
	union all
	select 		INDICADOR = 2
		,ID_AVISO = 0
		,ID_CATEGORIA = 0
		,DESCRIPCION = 'Adulto'
		,ordenado = 0
		,COUNT(1) AS TOTAL 
		,STR(COUNT(1)) AS ORDENADO		
		from @TABLAFILTRADA2 as TA
		where 
			case when @K_VISUALIZA_ADULTO = 1
				then 0 else
					case when TA.ADULTO=1
						then 1 else 0
					end
			end = 1
	UNION ALL			
	select 		INDICADOR = 3
		,KUB.ID_UBIGEO AS ID_AVISO
		,ID_CATEGORIA = 0
		,MAX(KUB.NOM) AS DESCRIPCION
		,ORDENADO = STR(COUNT(1))
		,COUNT(1) AS TOTAL 
		,STR(COUNT(1)) AS ORDENADO
		from @TABLAFILTRADA2 as TA
			inner join KO_AVISO KA (NOLOCK) ON KA.ID_AVISO=TA.ID_AVISO
			inner join KO_UBIGEO KUB (NOLOCK) on KUB.ID_UBIGEO=KA.ID_UBIGEO
		where 
			case when @K_VISUALIZA_ADULTO=1
			then 1
			else 
				case when TA.ADULTO = 1
					then 0 else 1
				end
		end = 1			
			
			GROUP BY
			KUB.ID_UBIGEO
	union all
	select 		INDICADOR = 4
		,KAG.L1 AS ID_AVISO
		,ID_CATEGORIA = 0
		,MAX(KAG.L1_NOM) AS DESCRIPCION
		,ORDENADO = STR(COUNT(1))
		,COUNT(1) AS TOTAL 
		,STR(COUNT(1)) AS ORDENADO
		from @TABLAFILTRADA2 as TA
			INNER JOIN KO_AGRUPADOR KAG (NOLOCK) ON KAG.ID_CATEGORIA=TA.ID_CATEGORIA
		where 
		case when @K_VISUALIZA_ADULTO=1
			then 1
			else 
				case when TA.ADULTO = 1
					then 0 else 1
				end
		end = 1			
			GROUP BY
			KAG.L1
			order by indicador,				
				TOTAL desc/*,
				ORDENADO1 desc	*/
EXEC KO_SP_BUSQUEDA_VISUALIZA @TABLAFILTRADA, @K_ID_MODULO, @K_ORDEN, @K_VISUALIZA_ADULTO, @K_NUM_REGISTROS_PAGINA
GO



/*
EXECUTE KO_SP_BUSQUEDA_FILTRO_MODULOS '-1', '', '', '-1', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '3', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO_MODULOS '-1', '', '', '-1', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '4', '-1', '1', '30', '0'
EXECUTE KO_SP_BUSQUEDA_FILTRO_MODULOS '-1', '', '', '-1', '', '', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '-1', '5', '-1', '1', '30', '0'
*/
ALTER PROCEDURE [dbo].[KO_SP_BUSQUEDA_FILTRO_MODULOS]
	@K_Cod_Aviso bigint =-1
	,@K_PalabraBuscar varchar(400) =''
	,@K_PalabraExcluida varchar(200) =''
	,@K_IdCategoria int =-1
	,@K_USUARIO varchar(50)=''
	,@K_Apodo varchar(50) =''
	,@K_TIPO_USUARIO INT =-1
	,@K_ID_RANGO_USUARIO INT =-1
	,@K_PRECIO1 DECIMAL =-1
	,@K_PRECIO2 DECIMAL =-1
	,@K_ID_UBIGEO NVARCHAR(50) ='-1'
	,@K_ID_MONEDA INT =-1
	,@K_ID_TIPO_PRODUCTO INT =-1
	,@K_ID_TIPO_AVISO INT =-1
	,@K_ID_MODULO INT =-1
	,@K_ORDEN INT = -1
	,@K_NUM_PAGINA INT =1
	,@K_NUM_REGISTROS_PAGINA INT =30
	,@K_VISUALIZA_ADULTO int =0
AS 
 
SET NOCOUNT ON 
SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED

DECLARE @TABLA as BUSQUEDATIPOTABLE
DECLARE @TABLAFILTRADA as BusquedaTabla
DECLARE @TABLAFILTRADA2 as BusquedaTabla
DECLARE @TABLAFILTROS table (
	ID_AVISO bigint,
	ID_CATEGORIA bigint,
	ADULTO int, 
	ORDENADO varchar(20),
	VISITAS int,
	SAL int) 

if (@K_ID_UBIGEO is not null) or (@K_ID_UBIGEO <> '') set @K_ID_UBIGEO = cast (@K_ID_UBIGEO as varchar(50))

declare 
	@cont int
	,@Maximo int 
	,@Minimo int
	,@TotalRegistros bigint
	,@FechaActual datetime = getdate()
	,@TipoCambio decimal(10,2)
		
set @Maximo = (@K_NUM_PAGINA * @K_NUM_REGISTROS_PAGINA) 
set @Minimo = @Maximo - (@K_NUM_REGISTROS_PAGINA - 1)
select top 1 @TipoCambio = VALOR_CAMB from KO_TIPO_CAMBIO where FEC_FIN is null

set @cont =
		case when @K_Cod_Aviso=-1 
			then 0 else 1
			end
		+
		case when @K_PalabraExcluida='' 
			then 0 else 1
			end
		+
		case when @K_USUARIO=''  
			then 0 else 1
			end
		+
		case when @K_Apodo=''  
			then 0 else 1
			end
		+
		case when @K_TIPO_USUARIO=-1 
			then 0 else 1
			end
		+
		case when @K_ID_RANGO_USUARIO=-1 
			then 0 else 1
			end
		+
		case when (@K_PRECIO1=-1 or  @K_PRECIO2=-1)
			then 0 else 1
			end
		+
		case when @K_ID_UBIGEO='-1'
			then 0 else 1
			end
		+
		case when @K_ID_MONEDA=-1  
			then 0 else 1
			end
		+
		case when @K_ID_TIPO_PRODUCTO=-1 
			then 0 else 1
			end
		+
		case when @K_ID_TIPO_AVISO=-1 
			then 0 else 1
			end
		+
		case when @K_ID_MODULO=-1  
			then 0 else 1
			end

INSERT INTO @TABLA 
	EXEC KO_SP_BUSQUEDA_AVISO_CATEGORIA @K_PalabraBuscar,@K_IdCategoria,@K_VISUALIZA_ADULTO

;
with Busqueda as (
	select 
		ROW_NUMBER () over (order by ORDENADO desc, VISITAS desc) as NroRegistro,
		ID_AVISO,
		ID_CATEGORIA,
		ADULTO,
		1 as registro,
		ORDENADO
	from 
		(
		select 
			TA.ID_AVISO,
			TA.ID_CATEGORIA,
			TA.ADULTO,
			ORDENADO = STR(
				case when @K_ORDEN=-1
					then KD.MONTO else
						case @K_ORDEN 
							WHEN 1 THEN 
								case when KA.ID_MONEDA = 1
									then 99999999 - PRECIO
									else 99999999 - (@TipoCambio * PRECIO)
									end																					
							WHEN 2 THEN
								case when KA.ID_MONEDA = 1
									then PRECIO
									else (@TipoCambio * PRECIO)
									end
							WHEN 3 THEN 99999999 - (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
							WHEN 4 THEN (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
							WHEN 5 THEN 99999999 - VISITAS 
							WHEN 6 THEN VISITAS							
							WHEN 7 THEN 99999999 - cast ( convert( char(8), FEC_FIN, 112 ) As integer )
							WHEN 8 THEN convert( char(8), FEC_FIN, 112)
						END 
				end,10,2 
				)
			,KA.VISITAS
			,sal= 
			(case when @K_Cod_Aviso=-1
			then 0 else 
				case when KA.ID_AVISO=@K_Cod_Aviso 
					then 1 else 0 
				end 
			end 
			+
			case when @K_PalabraExcluida=''
			then 0 else 
				case when CHARINDEX(@K_PalabraExcluida, KA.TIT ) > 0
		
			then 0 else 1 
				end 
			end 
			+
			case when @K_USUARIO=''
			then 0 else 
				case when CHARINDEX(@K_USUARIO, KUP.APODO + ' ,' + KU.NOM + ' ,' + KU.APEL+ ' ,' + cast(KUP.ID_USR as varchar(50))) > 0
					then 1 else 0
				end
			end			
			+
			case when @K_Apodo=''
			then 0 else 
				case when KUP.APODO = @K_Apodo
					then 1 else 0
				end
			end
			+
			case when @K_TIPO_USUARIO=-1
			then 0 else 
				case when KTU.ID_TIPO_USUARIO=@K_TIPO_USUARIO 
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_RANGO_USUARIO=-1
			then 0 else 
				case when KR.PUNTAJE>=@K_ID_RANGO_USUARIO
					then 1 else 0 
				end 
			end 
			+
			case when (@K_PRECIO1=-1 or @K_PRECIO2=-1)
			then 0 else 
				case when KA.ID_MONEDA = 1 					then case when (KA.PRECIO BETWEEN @K_PRECIO1 AND @K_PRECIO2)
						then 1 else 0 
						end
					else
						case when ((@TipoCambio * KA.PRECIO) BETWEEN @K_PRECIO1 AND @K_PRECIO2)
						then 1 else 0
						end
				end 
			end
			+
			case when @K_ID_UBIGEO='-1'
				then 0 else 
				case when CHARINDEX(',' + cast(KUB.ID_UBIGEO as varchar(10)) + ',', ',' + @K_ID_UBIGEO + ',') > 0
					then 1 else 0
				end 
			end 
			+
			case when @K_ID_MONEDA = -1
				then 0 else 
				case when KA.ID_MONEDA = @K_ID_MONEDA 
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_TIPO_PRODUCTO=-1
			then 0 else 
				case when KTP.ID_TIPO_PRODUCTO = @K_ID_TIPO_PRODUCTO 
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_TIPO_AVISO=-1
			then 0 else 
				case when KA.ID_TIPO_AVISO=@K_ID_TIPO_AVISO
					then 1 else 0 
				end 
			end 
			+
			case when @K_ID_MODULO=-1
				then 0 else 
					case when @K_ID_MODULO=3
						then 
							case when IMA.ID_MODULO=@K_ID_MODULO
								then 1 else 0
							end
						else 
							case when @K_ID_MODULO=4
								then 
									case when (KMD.ID_MOD_DESTAQUE>=1 and KMD.ID_MOD_DESTAQUE<=3 and KMD.ID_MOD_DESTAQUE<>2)
										then 1 else 0
									end
								else
									case when (KMD.ID_MOD_DESTAQUE>=2 and KMD.ID_MOD_DESTAQUE<=3)
										then 1 else 0
									end
							end
					end
			end
)
		from @TABLA TA
		inner join IN_MODULO_AVISO IMA on IMA.ID_AVISO=TA.ID_AVISO and IMA.COLOCADO=1		
		inner join KO_AVISO KA ON KA.ID_AVISO=IMA.ID_AVISO AND
			KA.EST>=1 AND KA.EST<=2		AND 
			KA.FLAG_MODERACION=0		AND 
			KA.ACTIVO=1					AND 
			KA.STOCK>0					AND
			KA.FEC_FIN > @FechaActual		
		inner join KO_AVISO_DESTAQUE KAD (NOLOCK) on KAD.ID_AVISO=KA.ID_AVISO and KAD.ACTIVO=1		
		inner join KO_DESTAQUE KD (NOLOCK) on KD.ID_DESTAQUE=KAD.ID_DESTAQUE and KD.ID_TIPO_DESTAQUE=2		
		inner join KO_MONEDA KM (NOLOCK) on KA.ID_MONEDA=KM.ID_MONEDA
		inner join KO_TIPO_PRODUCTO KTP (NOLOCK) on KTP.ID_TIPO_PRODUCTO=KA.ID_TIPO_PRODUCTO
		inner join KO_TIPO_AVISO KTA (NOLOCK) on KTA.ID_TIPO_AVISO=KA.ID_TIPO_AVISO
		inner join KO_USUARIO_PORTAL KUP (NOLOCK) on KUP.ID_USR=KA.ID_USR and ID_ESTADO_USUARIO=2
		inner join KO_USUARIO KU (NOLOCK) on KU.ID_USR=KA.ID_USR		
		inner join KO_REPUTACION KR (NOLOCK) on KR.ID_USR=KA.ID_USR
		inner join KO_TIPO_USUARIO KTU (NOLOCK) on KTU.ID_TIPO_USUARIO=KUP.ID_TIPO_USUARIO		
		inner join KO_UBIGEO KUB (NOLOCK) on KUB.ID_UBIGEO=KA.ID_UBIGEO
		
		left join KO_MODULO_DESTAQUE KMD on KMD.ID_MOD_DESTAQUE=IMA.ID_MOD_DESTAQUE
		inner join KO_FOTO KF on KF.ID_AVISO=TA.ID_AVISO and KF.PRIO=1
		
	) D
	where 
		sal = @cont and
		case when @K_VISUALIZA_ADULTO=1
			then 1
			else 
				case when D.ADULTO = 1
					then 0 else 1
				end
		end = 1
	)
	INSERT INTO @TABLAFILTRADA2
	select 
		INDICADOR = NroRegistro,
		BU.ID_AVISO,
		BU.ID_CATEGORIA,
		DESCRIPCION = 'Aviso',
		BU.ADULTO,
		TOTAL = (select max(NroRegistro) from Busqueda),
		ORDENADO
	from Busqueda BU
	
		
	insert into @TABLAFILTRADA
	select 
		INDICADOR = 1,
		ID_AVISO,
		ID_CATEGORIA,
		DESCRIPCION = 'Aviso',
		ADULTO,
		TOTAL,
		ORDENADO
	from @TABLAFILTRADA2 as TA
	where  
		INDICADOR BETWEEN @Minimo AND @Maximo
	union all
	select 		INDICADOR = 2
		,ID_AVISO = 0
		,ID_CATEGORIA = 0
		,DESCRIPCION = 'Adulto'
		,ordenado = 0
		,COUNT(1) AS TOTAL 
		,STR(COUNT(1)) AS ORDENADO		
		from @TABLAFILTRADA2 as TA
		where 
			case when @K_VISUALIZA_ADULTO = 1
				then 0 else
					case when TA.ADULTO=1
						then 1 else 0
					end
			end = 1
	UNION ALL			
	select 		INDICADOR = 3
		,KUB.ID_UBIGEO AS ID_AVISO
		,ID_CATEGORIA = 0
		,MAX(KUB.NOM) AS DESCRIPCION
		,ORDENADO = STR(COUNT(1))
		,COUNT(1) AS TOTAL 
		,STR(COUNT(1)) AS ORDENADO
		from @TABLAFILTRADA2 as TA
			inner join KO_AVISO KA (NOLOCK) ON KA.ID_AVISO=TA.ID_AVISO
			inner join KO_UBIGEO KUB (NOLOCK) on KUB.ID_UBIGEO=KA.ID_UBIGEO
		where 
			case when @K_VISUALIZA_ADULTO=1
			then 1
			else 
				case when TA.ADULTO = 1
					then 0 else 1
				end
		end = 1			
			
			GROUP BY
			KUB.ID_UBIGEO
	union all
	select 		INDICADOR = 4
		,KAG.L1 AS ID_AVISO
		,ID_CATEGORIA = 0
		,MAX(KAG.L1_NOM) AS DESCRIPCION
		,ORDENADO = STR(COUNT(1))
		,COUNT(1) AS TOTAL 
		,STR(COUNT(1)) AS ORDENADO
		from @TABLAFILTRADA2 as TA
			INNER JOIN KO_AGRUPADOR KAG (NOLOCK) ON KAG.ID_CATEGORIA=TA.ID_CATEGORIA
		where 
		case when @K_VISUALIZA_ADULTO=1
			then 1
			else 
				case when TA.ADULTO = 1
					then 0 else 1
				end
		end = 1			
			GROUP BY
			KAG.L1
			order by indicador,				
				TOTAL desc/*,
				ORDENADO1 desc	*/
EXEC KO_SP_BUSQUEDA_VISUALIZA_MODULOS @TABLAFILTRADA, @K_ID_MODULO, @K_ORDEN, @K_VISUALIZA_ADULTO, @K_NUM_REGISTROS_PAGINA
GO



ALTER PROCEDURE [dbo].[KO_SP_BUSQUEDA_VISUALIZA]
	@K_TABLA BusquedaTabla readonly
	,@K_ID_MODULO int =-1
	,@K_ORDEN int =-1
	,@K_ADULTO int =0
	,@K_NUM_REGISTROS_PAGINA int =30
AS 
 
SET NOCOUNT ON 
SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED
 
/*******************************************************   
*Descripcion: procedimiento que realiza la busqueda avanzada de los avisos 
			  de acuerdo a los filtros ingresados por el usuario 
*Fecha Crea: 18/02/2010   
*Fecha Mod:  06/04/2010 (BL: Se ha realizado modificaciones con with y una tabla temporal)  
*Parametros:
	@K_PalabraBuscar	: palabra clave o el número del ítem, se relaciona con titulo del aviso,  
	@K_IdCategoria		: Id de la categoria seleccionada, -1: Todos, 
 
*Autor: Manuel Francia 
*Versión: 1.0 (Kotear)   
********************************************************/  
declare @ofertas int =0
	,@FechaActual datetime = getdate()
	,@TipoCambio decimal(10,2)
select top 1 @TipoCambio = VALOR_CAMB from KO_TIPO_CAMBIO where FEC_FIN is null

select
	TOP (@K_NUM_REGISTROS_PAGINA)
	indicador = 1
	,KA.ID_AVISO AS ID_AVISO
	,TA.ID_CATEGORIA AS ID_CATEGORIA
	,DESCRIPCION= 'Avisos'
	,TOTAL = TA.TOTAL
	,KA.TIT, KA.SUBTIT, KA.URL, KA.TAG, KA.PRECIO, KA.ID_DURACION, KA.VISITAS, KA.FEC_PUB, KA.FEC_FIN, KA.ID_REPUBLICACION		
	,KM.ID_MONEDA, KM.SIMB,
	KTP.ID_TIPO_PRODUCTO, KTP.DES AS TIPOPRODUCTO, 
	KTA.ID_TIPO_AVISO, KTA.DES AS TIPOAVISO,
	KU.NOM+' '+KU.APEL AS USUARIO
	,KUP.ID_USR, KUP.APODO
	,KUR.ID_USUARIO_RANGO	
	,KR.PUNTAJE,
	case when KA.ID_AVISO <> ''  
		then (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
		else 0 
	end AS NUM_OFERTAS
	,KTU.ID_TIPO_USUARIO, KTU.DES AS TIPOUSUARIO, KTU.ICON,
	KUB.ID_UBIGEO, KUB.NOM,		
	case when KA.FEC_FIN <> ''
		then (select DBO.KO_FN_CADUCIDAD(KA.FEC_FIN))
		else '' end AS CADUCIDAD,
	KE.DES AS ESTILO,
	KD.ID_DESTAQUE, 
	KD.FOTO,
	KD.MONTO AS PRI,
	KUR.SIMBOLO	
	,CASE WHEN KF.ID_FOTO IS NULL THEN 'none.gif' ELSE CAST(KUP.ID_USR AS VARCHAR(30))+'/'+KF.NOM END AS IMAGEN
	,ordenado = STR(
	case when @K_ORDEN=-1
		then KD.MONTO else
			case @K_ORDEN 
					WHEN 1 THEN 
						case when KA.ID_MONEDA = 1
							then 99999999 - PRECIO
							else 99999999 - (@TipoCambio * PRECIO)
							end																					
					WHEN 2 THEN
						case when KA.ID_MONEDA = 1
							then PRECIO
							else (@TipoCambio * PRECIO)
							end
					WHEN 3 THEN 99999999 - (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
					WHEN 4 THEN (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
					WHEN 5 THEN 99999999 - VISITAS 
					WHEN 6 THEN VISITAS
					WHEN 7 THEN 99999999 - cast ( convert( char(8), FEC_FIN, 112 ) As integer )
					WHEN 8 THEN convert( char(8), FEC_FIN, 112)
			END 
	end,10,2 )
	,ORDENADO1 = KA.VISITAS
	/*,ORDENADO = '1'
	,ORDENADO1 = 1*/
	from @K_TABLA as TA
	inner join KO_AVISO KA (NOLOCK)
		ON TA.ID_AVISO=KA.ID_AVISO and  
		KA.EST>=1 AND KA.EST<=2		AND 
		KA.FLAG_MODERACION=0		AND 
		KA.ACTIVO=1					AND 
		KA.STOCK>0					AND
		KA.FEC_FIN > @FechaActual
	inner join KO_MONEDA KM (NOLOCK)
		on KA.ID_MONEDA=KM.ID_MONEDA
	inner join KO_TIPO_PRODUCTO KTP (NOLOCK)
		on KTP.ID_TIPO_PRODUCTO=KA.ID_TIPO_PRODUCTO
	inner join KO_TIPO_AVISO KTA (NOLOCK)
		on KTA.ID_TIPO_AVISO=KA.ID_TIPO_AVISO
	inner join KO_USUARIO_PORTAL KUP (NOLOCK)
		on KUP.ID_USR=KA.ID_USR and KUP.ID_ESTADO_USUARIO=2		 
	inner join KO_USUARIO KU (NOLOCK)
		on KU.ID_USR=KUP.ID_USR
	inner join KO_REPUTACION KR (NOLOCK)
		on KR.ID_USR=KUP.ID_USR
	inner join KO_UBIGEO KUB (NOLOCK)
		on KUB.ID_UBIGEO=KA.ID_UBIGEO
	inner join KO_TIPO_USUARIO KTU (NOLOCK)
		on KTU.ID_TIPO_USUARIO=KUP.ID_TIPO_USUARIO	
	inner join KO_AVISO_DESTAQUE KAD (NOLOCK)
		on KAD.ID_AVISO=KA.ID_AVISO and KAD.ACTIVO=1	inner join KO_DESTAQUE KD (NOLOCK)
		on KD.ID_DESTAQUE=KAD.ID_DESTAQUE AND (KD.ID_TIPO_DESTAQUE=2)				
	inner join KO_ESTILO KE (NOLOCK)
		on KE.ID_ESTILO=KD.ID_ESTILO			
	inner join KO_USUARIO_RANGO KUR (NOLOCK)
		on KUR.ID_USUARIO_RANGO=KUP.ID_USUARIO_RANGO
	left join KO_FOTO KF (NOLOCK)
		ON KF.ID_AVISO=KA.ID_AVISO and KF.PRIO=1
	where 
		TA.INDICADOR = 1 and
		case when @K_ADULTO=1
			then 1
			else case when TA.ADULTO = 1
					then 0 else 1
				end
			end = 1	
UNION ALL			
select 		INDICADOR = 2
		,TA.ID_AVISO AS ID_AVISO
		,TA.ID_CATEGORIA AS ID_CATEGORIA
		,TA.DESCRIPCION AS DESCRIPCION
		,TOTAL AS TOTAL 
/*
		indicador = 2
		,ID_AVISO=0
		,DESCRIPCION = 'Adulto'
		,COUNT(1) AS TOTAL */
		,TIT='', SUBTIT='', URL='', TAG='', PRECIO=0, ID_DURACION=0, VISITAS=0, FEC_PUB=null, 
		FEC_FIN=null, ID_REPUBLICACION=0,
		ID_MONEDA=0, SIMB='',
		ID_TIPO_PRODUCTO=0, TIPOPRODUCTO='', 
		ID_TIPO_AVISO=0, TIPOAVISO='',
		USUARIO='',
		ID_USR=0, APODO='', ID_USUARIO_RANGO=0,
		PUNTAJE=0,
		NUM_OFERTAS=0,
		ID_TIPO_USUARIO=0, TIPOUSUARIO='', ICON='',
		ID_UBIGEO=0, NOM='',
		CADUCIDAD='',
		ESTILO='',
		ID_DESTAQUE=0, 
		FOTO=0,
		PRI=0.00,
		SIMBOLO=''
		,IMAGEN=''
		,ordenado = 'Adulto'
		,ORDENADO1 = 0
		from @K_TABLA as TA
		where TA.INDICADOR = 2/* and
			case when @K_ADULTO = 1
				then 0 else
					case when TA.ADULTO=1
						then 1 else 0
					end
			end = 1*/
UNION ALL		
select 		INDICADOR = 3
		,TA.ID_AVISO AS ID_AVISO
		,TA.ID_CATEGORIA AS ID_CATEGORIA
		,TA.DESCRIPCION AS DESCRIPCION
		,TOTAL AS TOTAL 
/*
		indicador = 3
		,KUB.ID_UBIGEO AS ID_AVISO
		,MAX(KUB.NOM) AS DESCRIPCION
		,COUNT(1) AS TOTAL */
		,TIT='', SUBTIT='', URL='', TAG='', PRECIO=0, ID_DURACION=0, VISITAS=0, FEC_PUB=null, 
		FEC_FIN=null, ID_REPUBLICACION=0,
		ID_MONEDA=0, SIMB='',
		ID_TIPO_PRODUCTO=0, TIPOPRODUCTO='', 
		ID_TIPO_AVISO=0, TIPOAVISO='',
		USUARIO='',
		ID_USR=0, APODO='', ID_USUARIO_RANGO=0,
		PUNTAJE=0,
		NUM_OFERTAS=0,
		ID_TIPO_USUARIO=0, TIPOUSUARIO='', ICON='',
		ID_UBIGEO=0, NOM='',
		CADUCIDAD='',
		ESTILO='',
		ID_DESTAQUE=0, 
		FOTO=0,
		PRI=0.00,
		SIMBOLO=''
		,IMAGEN=''
		,ORDENADO = '1'-- STR(COUNT(1))
		,ORDENADO1 = 0
		from @K_TABLA as TA
			inner join KO_AVISO KA ON KA.ID_AVISO=TA.ID_AVISO
			inner join KO_UBIGEO KUB on KUB.ID_UBIGEO=KA.ID_UBIGEO
		where TA.indicador=3
			/*GROUP BY
			KUB.ID_UBIGEO*/
UNION ALL
select 		INDICADOR = 4
		,TA.ID_AVISO AS ID_AVISO
		,TA.ID_CATEGORIA AS ID_CATEGORIA
		,TA.DESCRIPCION AS DESCRIPCION
		,TOTAL AS TOTAL 
		/*indicador = 4
		,KAG.L1 AS ID_AVISO
		,MAX(KAG.L1_NOM) AS DESCRIPCION
		,COUNT(1) AS TOTAL */
		,TIT='', SUBTIT='', URL='', TAG='', PRECIO=0, ID_DURACION=0, VISITAS=0, FEC_PUB=null, 
		FEC_FIN=null, ID_REPUBLICACION=0,
		ID_MONEDA=0, SIMB='',
		ID_TIPO_PRODUCTO=0, TIPOPRODUCTO='', 
		ID_TIPO_AVISO=0, TIPOAVISO='',
		USUARIO='',
		ID_USR=0, APODO='', ID_USUARIO_RANGO=0,
		PUNTAJE=0,
		NUM_OFERTAS=0,
		ID_TIPO_USUARIO=0, TIPOUSUARIO='', ICON='',
		ID_UBIGEO=0, NOM='',
		CADUCIDAD='',
		ESTILO='',
		ID_DESTAQUE=0, 
		FOTO=0,
		PRI=0.00,
		SIMBOLO=''
		,IMAGEN=''
		,ORDENADO = '1'--STR(COUNT(1))
		,ORDENADO1 = 0
		from @K_TABLA as TA
					where ta.indicador=4
			/*GROUP BY
			KAG.L1*/
			order by indicador,
				ORDENADO desc,
				ORDENADO1 desc
GO



ALTER PROCEDURE [dbo].[KO_SP_BUSQUEDA_VISUALIZA_MODULOS]
	@K_TABLA BusquedaTabla readonly
	,@K_ID_MODULO int =-1
	,@K_ORDEN int =-1
	,@K_ADULTO int =0
	,@K_NUM_REGISTROS_PAGINA int =30
AS 
 
SET NOCOUNT ON 
SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED
 
/*******************************************************   
*Descripcion: procedimiento que realiza la busqueda avanzada de los avisos 
			  de acuerdo a los filtros ingresados por el usuario 
*Fecha Crea: 18/02/2010   
*Fecha Mod:  06/04/2010 (BL: Se ha realizado modificaciones con with y una tabla temporal)  
*Parametros:
	@K_PalabraBuscar	: palabra clave o el número del ítem, se relaciona con titulo del aviso,  
	@K_IdCategoria		: Id de la categoria seleccionada, -1: Todos, 
 
*Autor: Manuel Francia 
*Versión: 1.0 (Kotear)   
********************************************************/  
declare @ofertas int =0
	,@FechaActual datetime = getdate()
	,@TipoCambio decimal(10,2)
select top 1 @TipoCambio = VALOR_CAMB from KO_TIPO_CAMBIO where FEC_FIN is null

select
	TOP (@K_NUM_REGISTROS_PAGINA)
	indicador = 1
	,KA.ID_AVISO AS ID_AVISO
	,TA.ID_CATEGORIA AS ID_CATEGORIA
	,DESCRIPCION= 'Avisos'
	,TOTAL = TA.TOTAL
	,KA.TIT, KA.SUBTIT, KA.URL, KA.TAG, KA.PRECIO, KA.ID_DURACION, KA.VISITAS, KA.FEC_PUB, KA.FEC_FIN, KA.ID_REPUBLICACION		
	,KM.ID_MONEDA, KM.SIMB,
	KTP.ID_TIPO_PRODUCTO, KTP.DES AS TIPOPRODUCTO, 
	KTA.ID_TIPO_AVISO, KTA.DES AS TIPOAVISO,
	KU.NOM+' '+KU.APEL AS USUARIO
	,KUP.ID_USR, KUP.APODO
	,KUR.ID_USUARIO_RANGO	
	,KR.PUNTAJE,
	case when KA.ID_AVISO <> ''  
		then (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
		else 0 
	end AS NUM_OFERTAS
	,KTU.ID_TIPO_USUARIO, KTU.DES AS TIPOUSUARIO, KTU.ICON,
	KUB.ID_UBIGEO, KUB.NOM,		
	case when KA.FEC_FIN <> ''
		then (select DBO.KO_FN_CADUCIDAD(KA.FEC_FIN))
		else '' end AS CADUCIDAD,
	KE.DES AS ESTILO,
	KD.ID_DESTAQUE, 
	KD.FOTO,
	KD.MONTO AS PRI,
	KUR.SIMBOLO	
	,CASE WHEN KF.ID_FOTO IS NULL THEN 'none.gif' ELSE CAST(KUP.ID_USR AS VARCHAR(30))+'/'+KF.NOM END AS IMAGEN
	,ordenado = STR(
	case when @K_ORDEN=-1
		then KD.MONTO else
			case @K_ORDEN 
					WHEN 1 THEN 
						case when KA.ID_MONEDA = 1
							then 99999999 - PRECIO
							else 99999999 - (@TipoCambio * PRECIO)
							end																					
					WHEN 2 THEN
						case when KA.ID_MONEDA = 1
							then PRECIO
							else (@TipoCambio * PRECIO)
							end
					WHEN 3 THEN 99999999 - (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
					WHEN 4 THEN (select count(1) from ko_oferta where id_aviso=KA.ID_AVISO and (est=1 or est=5)) 
					WHEN 5 THEN 99999999 - VISITAS 
					WHEN 6 THEN VISITAS
					WHEN 7 THEN 99999999 - cast ( convert( char(8), FEC_FIN, 112 ) As integer )
					WHEN 8 THEN convert( char(8), FEC_FIN, 112)
			END 
	end,10,2 )
	,ORDENADO1 = KA.VISITAS
	/*,ORDENADO = '1'
	,ORDENADO1 = 1*/
	from @K_TABLA as TA
	inner join KO_AVISO KA (NOLOCK)
		ON TA.ID_AVISO=KA.ID_AVISO and  
		KA.EST>=1 AND KA.EST<=2		AND 
		KA.FLAG_MODERACION=0		AND 
		KA.ACTIVO=1					AND 
		KA.STOCK>0					AND
		KA.FEC_FIN > @FechaActual
	inner join KO_MONEDA KM (NOLOCK)
		on KA.ID_MONEDA=KM.ID_MONEDA
	inner join KO_TIPO_PRODUCTO KTP (NOLOCK)
		on KTP.ID_TIPO_PRODUCTO=KA.ID_TIPO_PRODUCTO
	inner join KO_TIPO_AVISO KTA (NOLOCK)
		on KTA.ID_TIPO_AVISO=KA.ID_TIPO_AVISO
	inner join KO_USUARIO_PORTAL KUP (NOLOCK)
		on KUP.ID_USR=KA.ID_USR and KUP.ID_ESTADO_USUARIO=2		 
	inner join KO_USUARIO KU (NOLOCK)
		on KU.ID_USR=KUP.ID_USR
	inner join KO_REPUTACION KR (NOLOCK)
		on KR.ID_USR=KUP.ID_USR
	inner join KO_UBIGEO KUB (NOLOCK)
		on KUB.ID_UBIGEO=KA.ID_UBIGEO
	inner join KO_TIPO_USUARIO KTU (NOLOCK)
		on KTU.ID_TIPO_USUARIO=KUP.ID_TIPO_USUARIO	
	inner join KO_AVISO_DESTAQUE KAD (NOLOCK)
		on KAD.ID_AVISO=KA.ID_AVISO and KAD.ACTIVO=1	inner join KO_DESTAQUE KD (NOLOCK)
		on KD.ID_DESTAQUE=KAD.ID_DESTAQUE AND (KD.ID_TIPO_DESTAQUE=2)				
	inner join KO_ESTILO KE (NOLOCK)
		on KE.ID_ESTILO=KD.ID_ESTILO			
	inner join KO_USUARIO_RANGO KUR (NOLOCK)
		on KUR.ID_USUARIO_RANGO=KUP.ID_USUARIO_RANGO
	left join KO_FOTO KF (NOLOCK)
		ON KF.ID_AVISO=KA.ID_AVISO and KF.PRIO=1
	where 
		TA.INDICADOR = 1 and
		case when @K_ADULTO=1
			then 1
			else case when TA.ADULTO = 1
					then 0 else 1
				end
			end = 1	
UNION ALL			
select 		INDICADOR = 2
		,TA.ID_AVISO AS ID_AVISO
		,TA.ID_CATEGORIA AS ID_CATEGORIA
		,TA.DESCRIPCION AS DESCRIPCION
		,TOTAL AS TOTAL 
/*
		indicador = 2
		,ID_AVISO=0
		,DESCRIPCION = 'Adulto'
		,COUNT(1) AS TOTAL */
		,TIT='', SUBTIT='', URL='', TAG='', PRECIO=0, ID_DURACION=0, VISITAS=0, FEC_PUB=null, 
		FEC_FIN=null, ID_REPUBLICACION=0,
		ID_MONEDA=0, SIMB='',
		ID_TIPO_PRODUCTO=0, TIPOPRODUCTO='', 
		ID_TIPO_AVISO=0, TIPOAVISO='',
		USUARIO='',
		ID_USR=0, APODO='', ID_USUARIO_RANGO=0,
		PUNTAJE=0,
		NUM_OFERTAS=0,
		ID_TIPO_USUARIO=0, TIPOUSUARIO='', ICON='',
		ID_UBIGEO=0, NOM='',
		CADUCIDAD='',
		ESTILO='',
		ID_DESTAQUE=0, 
		FOTO=0,
		PRI=0.00,
		SIMBOLO=''
		,IMAGEN=''
		,ordenado = 'Adulto'
		,ORDENADO1 = 0
		from @K_TABLA as TA
		where TA.INDICADOR = 2/* and
			case when @K_ADULTO = 1
				then 0 else
					case when TA.ADULTO=1
						then 1 else 0
					end
			end = 1*/
UNION ALL		
select 		INDICADOR = 3
		,TA.ID_AVISO AS ID_AVISO
		,TA.ID_CATEGORIA AS ID_CATEGORIA
		,TA.DESCRIPCION AS DESCRIPCION
		,TOTAL AS TOTAL 
/*
		indicador = 3
		,KUB.ID_UBIGEO AS ID_AVISO
		,MAX(KUB.NOM) AS DESCRIPCION
		,COUNT(1) AS TOTAL */
		,TIT='', SUBTIT='', URL='', TAG='', PRECIO=0, ID_DURACION=0, VISITAS=0, FEC_PUB=null, 
		FEC_FIN=null, ID_REPUBLICACION=0,
		ID_MONEDA=0, SIMB='',
		ID_TIPO_PRODUCTO=0, TIPOPRODUCTO='', 
		ID_TIPO_AVISO=0, TIPOAVISO='',
		USUARIO='',
		ID_USR=0, APODO='', ID_USUARIO_RANGO=0,
		PUNTAJE=0,
		NUM_OFERTAS=0,
		ID_TIPO_USUARIO=0, TIPOUSUARIO='', ICON='',
		ID_UBIGEO=0, NOM='',
		CADUCIDAD='',
		ESTILO='',
		ID_DESTAQUE=0, 
		FOTO=0,
		PRI=0.00,
		SIMBOLO=''
		,IMAGEN=''
		,ORDENADO = '1'-- STR(COUNT(1))
		,ORDENADO1 = 0
		from @K_TABLA as TA
			inner join KO_AVISO KA ON KA.ID_AVISO=TA.ID_AVISO
			inner join KO_UBIGEO KUB on KUB.ID_UBIGEO=KA.ID_UBIGEO
		where TA.indicador=3
			/*GROUP BY
			KUB.ID_UBIGEO*/
UNION ALL
select 		INDICADOR = 4
		,TA.ID_AVISO AS ID_AVISO
		,TA.ID_CATEGORIA AS ID_CATEGORIA
		,TA.DESCRIPCION AS DESCRIPCION
		,TOTAL AS TOTAL 
		/*indicador = 4
		,KAG.L1 AS ID_AVISO
		,MAX(KAG.L1_NOM) AS DESCRIPCION
		,COUNT(1) AS TOTAL */
		,TIT='', SUBTIT='', URL='', TAG='', PRECIO=0, ID_DURACION=0, VISITAS=0, FEC_PUB=null, 
		FEC_FIN=null, ID_REPUBLICACION=0,
		ID_MONEDA=0, SIMB='',
		ID_TIPO_PRODUCTO=0, TIPOPRODUCTO='', 
		ID_TIPO_AVISO=0, TIPOAVISO='',
		USUARIO='',
		ID_USR=0, APODO='', ID_USUARIO_RANGO=0,
		PUNTAJE=0,
		NUM_OFERTAS=0,
		ID_TIPO_USUARIO=0, TIPOUSUARIO='', ICON='',
		ID_UBIGEO=0, NOM='',
		CADUCIDAD='',
		ESTILO='',
		ID_DESTAQUE=0, 
		FOTO=0,
		PRI=0.00,
		SIMBOLO=''
		,IMAGEN=''
		,ORDENADO = '1'--STR(COUNT(1))
		,ORDENADO1 = 0
		from @K_TABLA as TA
					where ta.indicador=4
			/*GROUP BY
			KAG.L1*/
			order by indicador,
				ORDENADO desc,
				ORDENADO1 desc
GO

