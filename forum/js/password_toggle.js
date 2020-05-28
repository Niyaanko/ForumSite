// コンテンツロード終了後にタグを取得する
document.addEventListener("DOMContentLoaded", function () {
	// タグを取得
	const passwordToggle = document.querySelector(".js-password-toggle");
	// checkboxが変更されたら発火
	if (passwordToggle != null) {
		passwordToggle.addEventListener("change", function () {
			const password = document.querySelector(".js-password");
			const passwordLabel = document.querySelector(".js-password-label");
			if (password.type === "password") {
				password.type = "text";
				passwordLabel.innerHTML = '<i class="fas fa-eye-slash"></i>';
			} else {
				password.type = "password";
				passwordLabel.innerHTML = '<i class="fas fa-eye"></i>';
			}
			password.focus();
		});
	}
	// タグを取得
	const passwordToggleConf = document.querySelector(".js-password-new-toggle");
	// checkboxが変更されたら発火
	if (passwordToggleConf != null) {
		passwordToggleConf.addEventListener("change", function () {
			const password = document.querySelector(".js-password-new");
			const passwordLabel = document.querySelector(".js-password-new-label");
			if (password.type === "password") {
				password.type = "text";
				passwordLabel.innerHTML = '<i class="fas fa-eye-slash"></i>';
			} else {
				password.type = "password";
				passwordLabel.innerHTML = '<i class="fas fa-eye"></i>';
			}
			password.focus();
		});
	}
});
