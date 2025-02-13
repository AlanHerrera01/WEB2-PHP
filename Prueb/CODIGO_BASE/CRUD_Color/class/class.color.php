<?php
class COLOR
{
	private $idColor;
	private $descripcion;
	private $con;

	function __construct($cn)
	{
		$this->con = $cn;
	}

	//*********************** 3.1 METODO update_color() **************************************************	

	public function update_color()
	{
		$this->idColor = $_POST['idColor'];
		$this->descripcion = $_POST['descripcion'];

		$sql = "UPDATE color SET descripcion='$this->descripcion' WHERE idColor=$this->idColor;";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("modificó");
		} else {
			echo $this->_message_error("al modificar");
		}
	}

	//*********************** 3.2 METODO save_color() **************************************************	

	public function save_color()
	{
		$this->descripcion = $_POST['descripcion'];

		$sql = "INSERT INTO color (descripcion) VALUES('$this->descripcion');";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("guardó");
		} else {
			echo $this->_message_error("guardar");
		}
	}

	//************************************* PARTE II ****************************************************	

	public function get_form($id = NULL)
	{
		if ($id == NULL) {
			$this->descripcion = NULL;
			$flag = NULL;
			$op = "new";
		} else {
			$sql = "SELECT * FROM color WHERE idColor=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			$num = $res->num_rows;
			if ($num == 0) {
				echo $this->_message_error("Intento de actualizar el color con id= " . $id);
				return;
			} else {
				$this->descripcion = $row['descripcion'];
				$flag = "disabled";
				$op = "update";
			}
		}
		
		$html = '
		<div class="container mt-5">
			<div class="card shadow-lg border-0 rounded-4">
				<div class="card-header bg-primary text-white text-center py-3">
					<h3><i class="fas fa-palette"></i> Registro de Color</h3>
				</div>
				<div class="card-body p-4">
					<form name="color" method="POST" action="index.php">
						<input type="hidden" name="idColor" value="' . $id . '">
						<input type="hidden" name="op" value="' . $op . '">
						
						<div class="mb-3">
							<label for="descripcion" class="form-label fw-bold"><i class="fas fa-id-badge me-2"></i> Descripción</label>
							<input type="text" class="form-control border-primary" id="descripcion" name="descripcion" value="' . $this->descripcion . '" required>
						</div>
						<div class="text-center">
							<button type="submit" name="Guardar" class="btn btn-primary w-100">
								<i class="fas fa-save"></i> Guardar
							</button>						
							<a href="index.php" class="btn btn-secondary mt-2">
							<i class="fas fa-arrow-left"></i> Volver
						    </a>
						</div>
					</form>
				</div>
			</div>
		</div>';
		
		return $html;
	}

	public function get_list()
	{
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		
		$html = '
		<div class="container mt-5">
			<div class="card shadow-lg border-0 rounded-4">
				<div class="card-header bg-dark text-white text-center py-3">
					<h3><i class="fas fa-list"></i> Lista de Colores</h3>
				</div>
				<div class="card-body p-4">
					<div class="d-flex justify-content-between mb-3">
						<a href="index.php?d=' . $d_new_final . '" class="btn btn-success">
							<i class="fas fa-plus-circle"></i> Nuevo Color
						</a>
						<a href="../index.html" class="btn btn-secondary">
							<i class="fas fa-arrow-left"></i> Menú Principal
						</a>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-center align-middle">
							<thead class="table-dark">
								<tr>
									<th>Descripción</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>';
		
		$sql = "SELECT idColor, descripcion FROM color;";
		$res = $this->con->query($sql);
		while ($row = $res->fetch_assoc()) {
			$d_del_final = base64_encode("del/" . $row['idColor']);
			$d_act_final = base64_encode("act/" . $row['idColor']);
			$html .= '
			<tr>
				<td>' . $row['descripcion'] . '</td>
				<td>
					<a href="index.php?d=' . $d_act_final . '" class="btn btn-warning btn-sm rounded-pill">
						<i class="fas fa-edit"></i> Editar
					</a>
					<a href="index.php?d=' . $d_del_final . '" class="btn btn-danger btn-sm rounded-pill">
						<i class="fas fa-trash"></i> Borrar
					</a>
				</td>
			</tr>';
		}
		$html .= '</tbody></table></div></div></div>';
		return $html;
	}

	public function delete_color($idColor)
	{
		$sql = "DELETE FROM color WHERE idColor=$idColor;";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("eliminó");
		} else {
			echo $this->_message_error("eliminar");
		}
	}

	private function _message_error($tipo)
	{
		$html = '
		<div class="container mt-5">
			<div class="alert alert-danger text-center" role="alert">
				<h4 class="alert-heading">Error al ' . $tipo . '.</h4>
				<p>Ocurrió un problema, intente nuevamente.</p>
				<hr>
				<a href="index.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Volver</a>
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
				<a href="index.php" class="btn btn-success"><i class="fas fa-check-circle"></i> Volver</a>
			</div>
		</div>';
		return $html;
	}
}
?>
