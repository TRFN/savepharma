function processar_login(){
	// msgbox("Login Ação","Esta função encontra-se em fase de implementação.","info");
	
	var $_POST = [(a=$("form[action]:first input"))[0].value,a[1].value];
	
	if(!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test($_POST[0])){
		msgbox("Email inválido","","error");
		return false;
	}
	
	if($_POST[1].length == 0){
		msgbox("Insira a senha","","info");
		return false;
	}
	
	$.post("/login/processar", {email: $_POST[0],password: $_POST[1]}, function(data){
			switch(String(data)){
				case "0":
					msgbox("Login incorreto!","Usuário e/ou senha inválidos. Tente novamente.","error");
				break;
				case "1":
					window.top.location.href='/home';
				break;
				case "2":
					msgbox("Conta desabilitada.","Desculpe, mas sua conta está desativada. Entre em contato com algum administrador do sistema e solicite a reativação.","warning");
				break;
			}
		});
	
}  