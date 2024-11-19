function sair() 
{
    var confirmou = confirm('Deseja realmente sair do sistema?');
    if (confirmou) 
    {
        window.location = 'src/logout.php';
    }
}
function getCookie(name) 
{
    let value = `; ${document.cookie}`;
    let parts = value.split(`; ${name}=`); 
    if (parts.length === 2) return parts.pop().split(';').shift(); 
}

// Pega o timestamp do login
let loginTime = getCookie('login_time'); 
if (loginTime) 
{
    // Converte o valor do cookie de string para número e de segundos para milissegundos
    loginTime = parseInt(loginTime) * 1000;
    // Define o tempo máximo de sessão (30 minutos em milissegundos)
    let sessionDuration = 30 * 60 * 1000;
    function updateTimer() 
    {
        let now = new Date().getTime();
        let elapsedTime = now - loginTime; // Tempo decorrido desde o login
        let timeRemaining = sessionDuration - elapsedTime; // Tempo restante

        if (timeRemaining > 0) 
        {
            let minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
            let sessionText = `Tempo restante da sessão: ${minutes}m ${seconds}s`;
            // Atualiza tanto no layout principal quanto no offcanvas
            document.getElementById("temp_session").innerHTML = sessionText;
            document.getElementById("temp_session_mobile").innerHTML = sessionText;
        } 
        else 
        {
            clearInterval(sessionInterval); // Limpa o setInterval para evitar o loop
            document.getElementById("temp_session").innerHTML = "Sessão expirada.";
            document.getElementById("temp_session_mobile").innerHTML = "Sessão expirada.";
            alert("Sua sessão expirou. Por favor, efetue o login novamente.");
            window.location = 'index.php';
        }
    }
    // Atualiza o timer a cada segundo
    let sessionInterval = setInterval(updateTimer, 100); 
} 
else 
{
    document.getElementById("temp_session").innerHTML = "Nenhum cookie de login encontrado.";
    document.getElementById("temp_session_mobile").innerHTML = "Nenhum cookie de login encontrado.";
    window.location = 'index.php';
}