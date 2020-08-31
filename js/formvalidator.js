let fName = document.getElementById("fname");
let lName = document.getElementById("lname");
let email = document.getElementById("email");

function isValid(str) {
	return /^[A-zÀ-ÿ]+$/g.test(str);
}

function testFname() {
	if (fName.value.length > 2 && isValid(fName.value)) {
		document.getElementById("label-fname").innerHTML = "First name";
		document.getElementById("label-fname").style.color = "green";
		fName.style.borderColor = "green";
		fName.setCustomValidity("");
	}

	else {

		if (fName.value.length < 3) {
			document.getElementById("label-fname").innerHTML = "[First name is too short]";
			document.getElementById("label-fname").style.color = "red";
			fName.setCustomValidity("Please enter your first name (at least 3 characters)");
		}
		else if (!isValid(fName.value)) {
			document.getElementById("label-fname").innerHTML = "[First name must not contain special characters]";
			document.getElementById("label-fname").style.color = "red";
			fName.setCustomValidity("Your firstname must not contain special characters");
		}

	}
}


function testLname() {
	if (lName.value.length > 2 && isValid(lName.value)) {
		lName.style.borderColor = "green";
		document.getElementById("label-lname").innerHTML = "Last name";
		document.getElementById("label-lname").style.color = "green";
		lName.setCustomValidity("");
	}

	else {

		if (lName.value.length < 3) {
			document.getElementById("label-lname").innerHTML = "[Last name is too short]";
			document.getElementById("label-lname").style.color = "red";
			lName.setCustomValidity("Please enter your first name (at least 3 characters)");
		}
		else if (!isValid(lName.value)) {
			document.getElementById("label-lname").innerHTML = "[Last name must not contain special characters]";
			document.getElementById("label-lname").style.color = "red";
			lName.setCustomValidity("Your firstname must not contain special characters");
		}

	}
}

function testEmail() {

	const regex = /^[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)*@[a-z0-9]+(\-[a-z0-9]+)*(\.[a-z0-9]+(\-[a-z0-9]+)*)*\.[a-z]{2,4}$/;

	if (regex.test(email.value)) {
		email.style.borderColor = "green";
		document.getElementById("label-email").innerHTML = "Email";
		document.getElementById("label-email").style.color = "green";
		email.setCustomValidity("");
		return true;
	}
	else {
		email.style.borderColor = "red";
		document.getElementById("label-email").innerHTML = "Please enter a correct email. -> ex: skywalker@gmail.com";
		document.getElementById("label-email").style.color = "red";
		email.setCustomValidity("Please enter a correct email.");
		return false;
	}
}



