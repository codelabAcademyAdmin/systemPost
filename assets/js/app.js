function urlDir(url) {
	switch (url) {
		case null:
			window.history.back();
			break;
		case false:
			location.reload();
			break;
		default:
			window.location.href = url;
			break;
	}
}

function getUrl(value) {
	const urlObj = new URL(window.location.href);
	return urlObj.searchParams.get(value);
}

const sessionStatus = () => {
	$.ajax({
		url: "api/session.php",
		method: "GET",
		dataType: "json",
	}).done(function (res) {
		let session = res.session_estatus;
	});
};

const monedaCop = (numero) => {
	numero = parseInt(numero);
	numeroFormateado = numero.toLocaleString("es-CO", {
		style: "currency",
		currency: "COP",
		minimumFractionDigits: 0,
		maximumFractionDigits: 0,
	});
	return numeroFormateado;
};

const ValidateSessionPage = (url) => {
	const session = appSession.status();
	session != false ? urlDir(url) : urlDir("login?goBack=" + url);
};

function toggleDropdown() {
    const dropdown = document.querySelector('.dropdown-content');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function toggleModal() {
    const modal = document.querySelector('.content-modal');
    modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
}