// Custom JavaScript for Job Board

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Job search form handling
    const jobSearchForm = document.getElementById('job-search-form');
    if (jobSearchForm) {
        jobSearchForm.addEventListener('submit', function(e) {
            // Remove empty fields from form submission
            const inputs = jobSearchForm.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                if (input.value === '') {
                    input.disabled = true;
                }
            });
        });
    }

    // Salary range slider
    const salaryRange = document.getElementById('salary-range');
    const salaryValue = document.getElementById('salary-value');
    if (salaryRange && salaryValue) {
        salaryRange.addEventListener('input', function() {
            salaryValue.textContent = '$' + this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }

    // Job application form validation
    const applicationForm = document.getElementById('job-application-form');
    if (applicationForm) {
        applicationForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Resume validation
            const resumeInput = document.getElementById('resume');
            const resumeError = document.getElementById('resume-error');
            
            if (resumeInput && resumeInput.files.length === 0) {
                resumeError.textContent = 'Please upload your resume';
                resumeError.style.display = 'block';
                isValid = false;
            } else if (resumeInput && resumeInput.files.length > 0) {
                const fileSize = resumeInput.files[0].size / 1024 / 1024; // in MB
                const fileType = resumeInput.files[0].type;
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                if (fileSize > 2) {
                    resumeError.textContent = 'File size should be less than 2MB';
                    resumeError.style.display = 'block';
                    isValid = false;
                } else if (!validTypes.includes(fileType)) {
                    resumeError.textContent = 'Only PDF and Word documents are allowed';
                    resumeError.style.display = 'block';
                    isValid = false;
                } else {
                    resumeError.style.display = 'none';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Character counter for text areas
    const textAreas = document.querySelectorAll('textarea[data-max-chars]');
    textAreas.forEach(function(textArea) {
        const maxChars = textArea.getAttribute('data-max-chars');
        const counterElement = document.createElement('div');
        counterElement.className = 'text-muted small mt-1';
        counterElement.textContent = `0/${maxChars} characters`;
        textArea.parentNode.insertBefore(counterElement, textArea.nextSibling);
        
        textArea.addEventListener('input', function() {
            const currentChars = this.value.length;
            counterElement.textContent = `${currentChars}/${maxChars} characters`;
            
            if (currentChars > maxChars) {
                counterElement.classList.add('text-danger');
                this.classList.add('is-invalid');
            } else {
                counterElement.classList.remove('text-danger');
                this.classList.remove('is-invalid');
            }
        });
    });

    // Toggle password visibility
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const passwordInput = document.querySelector(this.getAttribute('data-target'));
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
});
