let currentStep = 1;

function changeStep(step) {
    const steps = document.querySelectorAll('.form-step');
    steps[currentStep - 1].classList.remove('active');
    currentStep += step;

    currentStep = Math.max(1, Math.min(currentStep, steps.length));

    steps[currentStep - 1].classList.add('active');
    document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
    document.getElementById('nextBtn').style.display = currentStep === steps.length ? 'none' : 'inline-block';
    document.getElementById('submitBtn').style.display = currentStep === steps.length ? 'inline-block' : 'none';
}

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '👁️';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '🙈';
    }
}

document.getElementById("district").addEventListener("change", function () {
    const district = this.value;
    const categorySelect = document.getElementById("category");

    // Clear previous options
    categorySelect.innerHTML = `<option value="">Select a Thana</option>`;

    // Populate options based on the selected district
    if (district === "CTG") {
        categorySelect.innerHTML += `
            <option value="Kotwali">Kotwali</option>
            <option value="Bandar">Bandar</option>
        `;
    } else if (district === "DHAKA") {
        categorySelect.innerHTML += `
            <option value="Tejgaon">Tejgaon</option>
            <option value="Gulshan">Gulshan</option>
        `;
    } else if (district === "RAJ") {
        categorySelect.innerHTML += `
            <option value="Rajpara">Rajpara</option>
            <option value="Boalia">Boalia</option>
        `;
    }
});
