<?php
class MARCA
{
	private $idMarca;
	private $descripcion;
	private $con;

	function __construct($cn)
	{
		$this->con = $cn;
	}


	//*********************** 3.1 METODO update_marca() **************************************************	

	public function update_marca()
	{
		$this->idMarca = $_POST['idMarca'];
		$this->descripcion = $_POST['descripcion'];

		$sql = "UPDATE marca SET descripcion='$this->descripcion'
				WHERE idMarca=$this->idMarca;";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("modificó");
		} else {
			echo $this->_message_error("al modificar");
		}
	}


	//*********************** 3.2 METODO save_marca() **************************************************	

	public function save_marca()
	{
		$this->descripcion = $_POST['descripcion'];

		$sql = "INSERT INTO marca (descripcion) VALUES('$this->descripcion');";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("guardó");
		} else {
			echo $this->_message_error("guardar");
		}
	}


	//*********************** 3.3 METODO _get_name_file() **************************************************	

	private function _get_name_file($nombre_original, $tamanio)
	{
		$tmp = explode(".", $nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm - 1]; //Extraer la última posición del arreglo.
		$cadena = "";
		for ($i = 1; $i <= $tamanio; $i++) {
			$c = rand(65, 122);
			if (($c >= 91) && ($c <= 96)) {
				$c = NULL;
				$i--;
			} else {
				$cadena .= chr($c);
			}
		}
		return $cadena . "." . $ext;
	}


	//*************************************** PARTE I ************************************************************

	private function _get_combo_db($tabla, $valor, $etiqueta, $nombre, $defecto)
	{
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while ($row = $res->fetch_assoc()) {
			$html .= ($defecto == $row[$valor]) ? '<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

	private function _get_combo_anio($nombre, $anio_inicial, $defecto)
	{
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for ($i = $anio_inicial; $i <= $anio_actual; $i++) {
			$html .= ($i == $defecto) ? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n" : '<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

	private function _get_radio($arreglo, $nombre, $defecto)
	{
		$html = '<table border=0 align="left">';
		foreach ($arreglo as $etiqueta) {
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
			if ($defecto == NULL) {
				$html .= '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
			} else {
				$html .= ($defecto == $etiqueta) ? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
			}
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}


	//************************************* PARTE II ****************************************************	

	public function get_form($id = NULL)
	{
		if ($id == NULL) {
			$this->descripcion = NULL;
			$flag = NULL;
			$op = "new";
		} else {
			$sql = "SELECT * FROM marca WHERE idMarca=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			$num = $res->num_rows;
			if ($num == 0) {
				$mensaje = "Intento de actualizar la marca con id= " . $id;
				echo $this->_message_error($mensaje);
			} else {
				$this->descripcion = $row['descripcion'];
				$flag = "disabled";
				$op = "update";
			}
		}
		
		$html = '
		<div class="container mt-5">
			<div class="card shadow-lg border-0 rounded-lg">
				<div class="card-header bg-dark text-white text-center py-3">
					<h3><i class="fas fa-car-side"></i> Registro de Marca</h3>
				</div>
				<div class="card-body bg-light p-4">
					<form name="marca" method="POST" action="index.php">
						<input type="hidden" name="idMarca" value="' . $id . '">
						<input type="hidden" name="op" value="' . $op . '">
						
						<div class="mb-3">
							<label for="descripcion" class="form-label fw-bold"><i class="fas fa-id-badge me-2"></i> Descripción</label>
							<input type="text" class="form-control" id="descripcion" name="descripcion" value="' . $this->descripcion . '" required>
						</div>
						<div class="text-center">
							<button type="submit" name="Guardar" class="btn btn-success w-100">
								<i class="fas fa-save"></i> Guardar
							</button>						
							<a href="../index.html" class="btn btn-secondary">
							<i class="fas fa-arrow-left"></i> Menú Principal
						    </a>
						</div>
					</form>
				</div>
			</div>
		</div>
		';
		
		return $html;
	}

	public function get_list()
	{
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$d_menu = "menu";
		$d_menu_final = base64_encode($d_menu);
		$html = '
		<div class="container mt-5">
			<div class="card shadow-lg border-0 rounded-lg">
				<div class="card-header bg-dark text-white text-center py-3">
					<h3><i class="fas fa-list-alt"></i> Lista de Marcas</h3>
				</div>
				<div class="card-body bg-light p-4">
					<div class="mb-3 text-center">
						<a href="index.php?d=' . $d_new_final . '" class="btn btn-success">
							<i class="fas fa-plus-circle"></i> Nuevo
						</a>
						<a href="../index.html" class="btn btn-secondary">
							<i class="fas fa-arrow-left"></i> Menú Principal
						</a>
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-hover text-center">
							<thead class="table-dark">
								<tr>
									<th>Descripción</th>
									<th colspan="3">Acciones</th>
								</tr>
							</thead>
							<tbody>
		';
		
		$sql = "SELECT m.idMarca, m.descripcion FROM marca m;";
		$res = $this->con->query($sql);
		while ($row = $res->fetch_assoc()) {
			$d_del = "del/" . $row['idMarca'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['idMarca'];
			$d_act_final = base64_encode($d_act);
			$html .= '
			<tr>
				<td>' . $row['descripcion'] . '</td>
				<td>
					<a href="index.php?d=' . $d_del_final . '" class="btn btn-danger btn-sm rounded-pill">
						<i class="fas fa-trash-alt"></i> Borrar
					</a>
				</td>
				<td>
					<a href="index.php?d=' . $d_act_final . '" class="btn btn-warning btn-sm rounded-pill">
						<i class="fas fa-edit"></i> Actualizar
					</a>
				</td>
			</tr>';
		}
		$html .= '</tbody></table></div></div></div>';
		return $html;
	}
	

	public function delete_marca($idMarca)
	{
		$sql = "DELETE FROM marca WHERE idMarca=$idMarca;";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("ELIMINÓ");
		} else {
			echo $this->_message_error("eliminar");
		}
	}

	//*************************************************************************


	private function _message_error($tipo)
	{
		$html = '
		<div class="container mt-5">
			<div class="alert alert-danger text-center" role="alert">
				<h4 class="alert-heading">Error al ' . $tipo . '.</h4>
				<p>Favor contactar a soporte técnico.</p>
				<hr>
				<a href="index.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Regresar</a>
			</div>
		</div>';
		return $html;
	}
	
	private function _message_ok($tipo)
	{
		$html = '
		<div class="container mt-5">
			<div class="alert alert-success text-center" role="alert">
				 <h4 class="alert-heading">¡Registro ' . $tipo . ' exitosamente!</h4>
				 <a href="index.php" class="btn btn-success"><i class="fas fa-check-circle"></i> Regresar</a>
			</div>
		</div>';
		return $html;
	}
}
?>
