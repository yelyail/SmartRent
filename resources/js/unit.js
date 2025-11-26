// unit.js - Updated to avoid conflicts
function openAddUnitModal(propertyId) {
    const modalContent = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">New Unit</h2>
                            <p class="text-sm text-gray-500">Fill in the unit details</p>
                        </div>
                    </div>
                    <button onclick="closeAddUnitModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="addUnitForm" class="p-6 space-y-6">
                    <input type="hidden" name="prop_id" value="${propertyId}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="unit_name" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Unit A, Studio 101">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Number <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="unit_num" required min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., 101">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Type <span class="text-red-500">*</span>
                            </label>
                            <select name="unit_type" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="studio">Studio</option>
                                <option value="1-bedroom">1 Bedroom</option>
                                <option value="2-bedroom">2 Bedroom</option>
                                <option value="3-bedroom">3 Bedroom</option>
                                <option value="penthouse">Penthouse</option>
                                <option value="loft">Loft</option>
                                <option value="duplex">Duplex</option>
                                <option value="commercial">Commercial</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Area (sqm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="area_sqm" required min="1" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., 45.5">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" name="unit_price" required min="0" step="0.01"
                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="e.g., 15000.00">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="reserved">Reserved</option>
                                <option value="rented">Rented</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeAddUnitModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                            Add Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;

    // Create modal container if it doesn't exist
    let modalContainer = document.getElementById("addUnitModal");
    if (!modalContainer) {
        modalContainer = document.createElement("div");
        modalContainer.id = "addUnitModal";
        document.body.appendChild(modalContainer);
    }

    modalContainer.innerHTML = modalContent;

    // Add form submit handler
    document
        .getElementById("addUnitForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            addPropertyUnit(propertyId);
        });
}

// Edit Property Modal
async function openEditPropertyModal(propertyId) {
    try {
        console.log("Opening edit modal for property:", propertyId);
        const response = await fetch(`/landlord/properties/${propertyId}/edit`);
        console.log("Edit response status:", response.status);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const property = await response.json();
        console.log("Property data for edit:", property);

        // Load units and devices data
        const [unitsResponse, devicesResponse] = await Promise.all([
            fetch(`/landlord/properties/${propertyId}/units`),
            fetch(`/landlord/properties/${propertyId}/devices`),
        ]);

        const units = unitsResponse.ok ? await unitsResponse.json() : [];
        const devices = devicesResponse.ok ? await devicesResponse.json() : [];

        const modalContent = `
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Edit Property - ${property.property_name}</h2>
                            <p class="text-sm text-gray-500">Update property information, units, and smart devices</p>
                        </div>
                    </div>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6">
                        <button id="editPropertyTab" class="tab-button border-b-2 border-blue-500 text-blue-600 px-1 py-4 text-sm font-medium" onclick="switchEditTab('property')">
                            Property Details
                        </button>
                        <button id="editUnitsTab" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 py-4 text-sm font-medium" onclick="switchEditTab('units')">
                            Units (${units.length})
                        </button>
                        <button id="editDevicesTab" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 py-4 text-sm font-medium" onclick="switchEditTab('devices')">
                            Smart Devices (${devices.length})
                        </button>
                    </nav>
                </div>

                <!-- Property Details Tab -->
                <div id="editPropertyContent" class="tab-content p-6">
                    <form id="editPropertyForm" action="/landlord/properties/${propertyId}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Name</label>
                                <input type="text" name="property_name" value="${property.property_name}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                                <select name="property_type" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="apartment" ${property.property_type === "apartment" ? "selected" : ""}>Apartment Complex</option>
                                    <option value="condo" ${property.property_type === "condo" ? "selected" : ""}>Condominium</option>
                                    <option value="townhouse" ${property.property_type === "townhouse" ? "selected" : ""}>Townhouse</option>
                                    <option value="single-family" ${property.property_type === "single-family" ? "selected" : ""}>Single Family Home</option>
                                    <option value="duplex" ${property.property_type === "duplex" ? "selected" : ""}>Duplex</option>
                                    <option value="commercial" ${property.property_type === "commercial" ? "selected" : ""}>Commercial</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Address</label>
                            <input type="text" name="property_address" value="${property.property_address}" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Price</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" name="property_price" value="${property.property_price}" required min="0" step="0.01"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Image</label>
                                <input type="file" name="property_image" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">Current image: ${property.property_image}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Description</label>
                            <textarea name="property_description" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">${property.property_description}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active" ${property.status === "active" ? "selected" : ""}>Active</option>
                                <option value="inactive" ${property.status === "inactive" ? "selected" : ""}>Inactive</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeEditModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Save Property Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Units Tab -->
                <div id="editUnitsContent" class="tab-content hidden p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Property Units</h3>
                        <button type="button" onclick="openAddUnitModal(${propertyId})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus text-sm"></i>
                            <span>New Unit</span>
                        </button>
                    </div>
                    
                    <div id="editUnitsList" class="space-y-4">
                        ${
                            units.length > 0
                                ? units
                                      .map(
                                          (unit) => `
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-semibold text-gray-900">${unit.unit_name}</h4>
                                    <div class="flex space-x-2">
                                        <button onclick="editUnit(${unit.unit_id})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteUnit(${unit.unit_id})" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">Unit #:</span>
                                        <span>${unit.unit_num}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Type:</span>
                                        <span class="capitalize">${unit.unit_type}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Area:</span>
                                        <span>${unit.area_sqm} sqm</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Price:</span>
                                        <span class="font-semibold">₱${parseFloat(unit.unit_price).toLocaleString()}</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="font-medium">Status:</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(unit.status)}">
                                            ${unit.status}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `,
                                      )
                                      .join("")
                                : `
                            <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <i class="fas fa-building text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500">No units added yet</p>
                                <p class="text-sm text-gray-400 mt-1">Click "Add Unit" to get started</p>
                            </div>
                        `
                        }
                    </div>
                </div>

                <!-- Smart Devices Tab -->
                <div id="editDevicesContent" class="tab-content hidden p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Smart Devices</h3>
                        <button type="button" onclick="openAddDeviceModal(${propertyId})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus text-sm"></i>
                            <span>Add Device</span>
                        </button>
                    </div>
                    
                    <div id="editDevicesList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${
                            devices.length > 0
                                ? devices
                                      .map(
                                          (device) => `
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-semibold text-gray-900">${device.device_name}</h4>
                                    <div class="flex space-x-2">
                                        <button onclick="editDevice(${device.device_id})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteDevice(${device.device_id})" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Type:</span>
                                        <span class="capitalize">${device.device_type}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Status:</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium ${device.connection_status === "online" ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}">
                                            ${device.connection_status}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Power:</span>
                                        <span class="capitalize ${device.power_status === "on" ? "text-green-600" : "text-red-600"}">${device.power_status}</span>
                                    </div>
                                    ${
                                        device.model
                                            ? `
                                    <div class="flex justify-between">
                                        <span>Model:</span>
                                        <span>${device.model}</span>
                                    </div>
                                    `
                                            : ""
                                    }
                                    ${
                                        device.battery_level !== null
                                            ? `
                                    <div class="flex justify-between">
                                        <span>Battery:</span>
                                        <span>${device.battery_level}%</span>
                                    </div>
                                    `
                                            : ""
                                    }
                                </div>
                            </div>
                        `,
                                      )
                                      .join("")
                                : `
                            <div class="col-span-2 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <i class="fas fa-plug text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500">No smart devices connected</p>
                                <p class="text-sm text-gray-400 mt-1">Click "Add Device" to get started</p>
                            </div>
                        `
                        }
                    </div>
                </div>
            </div>
        `;

        document.getElementById("editPropertyModal").innerHTML = modalContent;
        document.getElementById("editPropertyModal").classList.remove("hidden");
        document.getElementById("editPropertyModal").classList.add("flex");
        document.body.style.overflow = "hidden";
    } catch (error) {
        console.error("Error loading property for edit:", error);
        console.error("Error details:", error.message);
        alert(
            "Error loading property for editing. Please try again. Error: " +
                error.message,
        );
    }
}

function closeAddUnitModal() {
    const modalContainer = document.getElementById("addUnitModal");
    if (modalContainer) {
        modalContainer.remove();
    }
}

async function addPropertyUnit(propertyId) {
    try {
        const form = document.getElementById("addUnitForm");
        const formData = new FormData(form);

        const unitData = {
            prop_id: propertyId,
            unit_name: formData.get("unit_name"),
            unit_num: parseInt(formData.get("unit_num")),
            unit_type: formData.get("unit_type"),
            area_sqm: parseFloat(formData.get("area_sqm")),
            unit_price: parseFloat(formData.get("unit_price")),
            status: formData.get("status"),
        };

        console.log("Sending unit data:", unitData);

        const response = await fetch(
            "/landlord/properties/" + propertyId + "/units",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: JSON.stringify(unitData),
            },
        );

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to add unit");
        }

        if (!result.success) {
            throw new Error(result.message || "Failed to add unit");
        }

        // Close modal and refresh units list
        closeAddUnitModal();
        refreshUnitsList(propertyId);

        // Show success message
        alert("Unit added successfully!");
    } catch (error) {
        console.error("Error adding unit:", error);
        alert("Error adding unit: " + error.message);
    }
}

// Tab switching function for edit modal
function switchEditTab(tabName) {
    document
        .querySelectorAll("#editPropertyModal .tab-content")
        .forEach((content) => {
            content.classList.add("hidden");
        });

    // Remove active styles from all tabs
    document
        .querySelectorAll("#editPropertyModal .tab-button")
        .forEach((button) => {
            button.classList.remove("border-blue-500", "text-blue-600");
            button.classList.add("border-transparent", "text-gray-500");
        });

    // Show selected tab content
    document
        .getElementById(
            `edit${tabName.charAt(0).toUpperCase() + tabName.slice(1)}Content`,
        )
        .classList.remove("hidden");

    // Add active styles to selected tab
    document
        .getElementById(
            `edit${tabName.charAt(0).toUpperCase() + tabName.slice(1)}Tab`,
        )
        .classList.add("border-blue-500", "text-blue-600");
    document
        .getElementById(
            `edit${tabName.charAt(0).toUpperCase() + tabName.slice(1)}Tab`,
        )
        .classList.remove("border-transparent", "text-gray-500");
}

function getStatusColor(status) {
    const colors = {
        available: "bg-green-100 text-green-800",
        occupied: "bg-blue-100 text-blue-800",
        maintenance: "bg-yellow-100 text-yellow-800",
        reserved: "bg-purple-100 text-purple-800",
        rented: "bg-indigo-100 text-indigo-800",
        unavailable: "bg-red-100 text-red-800",
        inactive: "bg-gray-100 text-gray-800",
    };
    return colors[status] || "bg-gray-100 text-gray-800";
}

async function editPropertyUnit(unitId) {
    try {
        // Fetch unit data
        const response = await fetch(`/landlord/units/${unitId}/edit`);

        if (!response.ok) {
            throw new Error("Failed to fetch unit data");
        }

        const unit = await response.json();

        const modalContent = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Edit Unit</h2>
                                <p class="text-sm text-gray-500">Update unit information</p>
                            </div>
                        </div>
                        <button onclick="closeEditUnitModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="editUnitForm" class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Name</label>
                                <input type="text" name="unit_name" value="${unit.unit_name}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Number</label>
                                <input type="number" name="unit_num" value="${unit.unit_num}" required min="1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Type</label>
                                <select name="unit_type" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="studio" ${unit.unit_type === "studio" ? "selected" : ""}>Studio</option>
                                    <option value="1-bedroom" ${unit.unit_type === "1-bedroom" ? "selected" : ""}>1 Bedroom</option>
                                    <option value="2-bedroom" ${unit.unit_type === "2-bedroom" ? "selected" : ""}>2 Bedroom</option>
                                    <option value="3-bedroom" ${unit.unit_type === "3-bedroom" ? "selected" : ""}>3 Bedroom</option>
                                    <option value="penthouse" ${unit.unit_type === "penthouse" ? "selected" : ""}>Penthouse</option>
                                    <option value="loft" ${unit.unit_type === "loft" ? "selected" : ""}>Loft</option>
                                    <option value="duplex" ${unit.unit_type === "duplex" ? "selected" : ""}>Duplex</option>
                                    <option value="commercial" ${unit.unit_type === "commercial" ? "selected" : ""}>Commercial</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Area (sqm)</label>
                                <input type="number" name="area_sqm" value="${unit.area_sqm}" required min="1" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                    <input type="number" name="unit_price" value="${unit.unit_price}" required min="0" step="0.01"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="reserved">Reserved</option>
                                    <option value="rented">Rented</option>
                                    <option value="unavailable">Unavailable</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeEditUnitModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Update Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        // Create modal container if it doesn't exist
        let modalContainer = document.getElementById("editUnitModal");
        if (!modalContainer) {
            modalContainer = document.createElement("div");
            modalContainer.id = "editUnitModal";
            document.body.appendChild(modalContainer);
        }

        modalContainer.innerHTML = modalContent;

        // Add form submit handler
        document
            .getElementById("editUnitForm")
            .addEventListener("submit", function (e) {
                e.preventDefault();
                updatePropertyUnit(unitId);
            });
    } catch (error) {
        console.error("Error loading unit for edit:", error);
        alert("Error loading unit for editing: " + error.message);
    }
}

function closeEditUnitModal() {
    const modalContainer = document.getElementById("editUnitModal");
    if (modalContainer) {
        modalContainer.remove();
    }
}

async function updatePropertyUnit(unitId) {
    try {
        const form = document.getElementById("editUnitForm");
        const formData = new FormData(form);

        const unitData = {
            unit_name: formData.get("unit_name"),
            unit_num: parseInt(formData.get("unit_num")),
            unit_type: formData.get("unit_type"),
            area_sqm: parseFloat(formData.get("area_sqm")),
            unit_price: parseFloat(formData.get("unit_price")),
            status: formData.get("status"),
        };

        const response = await fetch("/landlord/units/" + unitId, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(unitData),
        });

        if (!response.ok) {
            throw new Error("Failed to update unit");
        }

        // Close modal and refresh units list
        closeEditUnitModal();

        // Refresh the parent property edit modal
        const propertyId = document.querySelector(
            'input[name="prop_id"]',
        )?.value;
        if (propertyId) {
            refreshUnitsList(propertyId);
        }

        alert("Unit updated successfully!");
    } catch (error) {
        console.error("Error updating unit:", error);
        alert("Error updating unit: " + error.message);
    }
}

async function deletePropertyUnit(unitId) {
    if (
        !confirm(
            "Are you sure you want to archive this unit? The unit will be marked as inactive and hidden from active listings.",
        )
    ) {
        return;
    }

    try {
        const response = await fetch("/landlord/units/" + unitId + "/archive", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ status: "inactive" }),
        });

        if (!response.ok) {
            throw new Error("Failed to archive unit");
        }

        // Refresh units list
        const propertyId = document.querySelector(
            'input[name="prop_id"]',
        )?.value;
        if (propertyId) {
            refreshUnitsList(propertyId);
        }

        alert("Unit archived successfully!");
    } catch (error) {
        console.error("Error deactivating unit:", error);
        alert("Error deactivating unit: " + error.message);
    }
}

async function refreshUnitsList(propertyId) {
    try {
        const response = await fetch(
            `/landlord/properties/${propertyId}/units`,
        );

        if (!response.ok) {
            throw new Error("Failed to fetch units");
        }

        const units = await response.json();
        const unitsContainer = document.getElementById("editUnitsList");

        if (!unitsContainer) return;

        // Filter out inactive units (only show active ones)
        const activeUnits = units.filter((unit) => unit.status !== "inactive");

        if (activeUnits.length === 0) {
            unitsContainer.innerHTML = `
                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-building text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500">No active units</p>
                    <p class="text-sm text-gray-400 mt-1">Click "Add Unit" to get started</p>
                </div>
            `;
            return;
        }

        unitsContainer.innerHTML = activeUnits
            .map(
                (unit) => `
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-900">${unit.unit_name}</h4>
                    <div class="flex space-x-2">
                        <button onclick="editUnit(${unit.unit_id})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteUnit(${unit.unit_id})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Unit #:</span>
                        <span>${unit.unit_num}</span>
                    </div>
                    <div>
                        <span class="font-medium">Type:</span>
                        <span class="capitalize">${unit.unit_type}</span>
                    </div>
                    <div>
                        <span class="font-medium">Area:</span>
                        <span>${unit.area_sqm} sqm</span>
                    </div>
                    <div>
                        <span class="font-medium">Price:</span>
                        <span class="font-semibold">₱${parseFloat(unit.unit_price).toLocaleString()}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="font-medium">Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(unit.status)}">
                            ${unit.status}
                        </span>
                    </div>
                </div>
            </div>
        `,
            )
            .join("");
    } catch (error) {
        console.error("Error refreshing units list:", error);
    }
}
