// Make functions globally available by attaching them to window
window.togglePassword = function (fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(
        fieldId === "password" ? "password-toggle" : "confirm-password-toggle"
    );

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
};

window.removeFile = function (type, fileId, listId, inputId) {
    const fileList = document.getElementById(listId);
    const fileItem = fileList.querySelector(`[data-file-id="${fileId}"]`);

    if (fileItem) {
        fileItem.remove();
    }

    uploadedFiles[type] = null;
    document.getElementById(inputId).value = "";
};

// File management variables
const uploadedFiles = {
    main: null,
    proofOfIncome: null,
};

// Password strength indicator
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            const password = this.value;
            const strengthIndicator =
                document.getElementById("password-strength");

            // Simple strength calculation
            let strength = 0;
            if (password.length >= 12) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;

            if (strength < 50) {
                strengthIndicator.className =
                    "h-1 rounded-full mt-2 bg-red-500";
                strengthIndicator.style.width = strength + "%";
            } else if (strength < 75) {
                strengthIndicator.className =
                    "h-1 rounded-full mt-2 bg-yellow-500";
                strengthIndicator.style.width = strength + "%";
            } else {
                strengthIndicator.className =
                    "h-1 rounded-full mt-2 bg-green-500";
                strengthIndicator.style.width = strength + "%";
            }
        });
    }

    // File upload handlers
    const docPathInput = document.getElementById("doc_path");
    if (docPathInput) {
        docPathInput.addEventListener("change", function (e) {
            handleFileUpload(
                e.target.files[0],
                "main",
                "docFileList",
                "doc_path"
            );
        });
    }

    const proofOfIncomeInput = document.getElementById("proof_of_income");
    if (proofOfIncomeInput) {
        proofOfIncomeInput.addEventListener("change", function (e) {
            handleFileUpload(
                e.target.files[0],
                "proofOfIncome",
                "proofOfIncomeFileList",
                "proof_of_income"
            );
        });
    }

    // Check for Laravel session success message
    const successMessage = document.querySelector("[data-success]");
    if (successMessage) {
        Swal.fire({
            icon: "success",
            title: "Success!",
            text: successMessage.getAttribute("data-success"),
            confirmButtonText: "OK",
            confirmButtonColor: "#3B82F6",
        });
    }

    // Check for Laravel session error message
    const errorMessage = document.querySelector("[data-error]");
    if (errorMessage) {
        Swal.fire({
            icon: "error",
            title: "Registration Failed",
            text: errorMessage.getAttribute("data-error"),
            confirmButtonText: "Try Again",
            confirmButtonColor: "#EF4444",
        });
    }
});

function handleFileUpload(file, type, listId, inputId) {
    if (!file) return;

    // Validate file type
    const validTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "application/pdf",
    ];
    if (!validTypes.includes(file.type)) {
        showFileError(
            "Invalid file type. Please upload PDF, PNG, or JPG files."
        );
        document.getElementById(inputId).value = "";
        return;
    }

    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        showFileError("File size too large. Maximum size is 5MB.");
        document.getElementById(inputId).value = "";
        return;
    }

    // Clear previous file if exists
    if (uploadedFiles[type]) {
        removeFile(type, uploadedFiles[type]._fileId, listId, inputId);
    }

    uploadedFiles[type] = file;
    displayFileItem(file, type, listId, inputId);
}

function displayFileItem(file, type, listId, inputId) {
    const fileList = document.getElementById(listId);
    const fileId = Date.now() + Math.random();

    // Clear any existing files first
    fileList.innerHTML = "";

    const fileItem = document.createElement("div");
    fileItem.className =
        "flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-3";
    fileItem.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-file text-blue-500"></i>
            <span class="text-sm text-gray-700 truncate max-w-xs">${
                file.name
            }</span>
            <span class="text-xs text-gray-500">(${(
                file.size /
                1024 /
                1024
            ).toFixed(2)} MB)</span>
        </div>
        <button type="button" onclick="removeFile('${type}', ${fileId}, '${listId}', '${inputId}')" class="text-red-500 hover:text-red-700 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    `;
    fileItem.dataset.fileId = fileId;

    // Store file reference
    file._fileId = fileId;

    fileList.appendChild(fileItem);
}

function showFileError(message) {
    Swal.fire({
        icon: "error",
        title: "Upload Error",
        text: message,
        timer: 3000,
        showConfirmButton: false,
    });
}
