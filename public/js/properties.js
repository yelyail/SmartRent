// Properties Management
function filterProperties() {
    const searchInput = document
        .getElementById("searchInput")
        .value.toLowerCase();
    const statusFilter = document.getElementById("statusFilter").value;
    const propertyCards = document.querySelectorAll(".property-card");
    const noResults = document.getElementById("noResults");
    let visibleCount = 0;

    propertyCards.forEach((card) => {
        const propertyName = card.getAttribute("data-name").toLowerCase();
        const propertyStatus = card.getAttribute("data-status");
        const propertyLocation = card
            .getAttribute("data-location")
            .toLowerCase();

        const matchesSearch =
            propertyName.includes(searchInput) ||
            propertyLocation.includes(searchInput) ||
            searchInput === "";

        const matchesStatus =
            statusFilter === "" || propertyStatus === statusFilter;

        if (matchesSearch && matchesStatus) {
            card.style.display = "block";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });

    if (visibleCount === 0) {
        noResults.classList.remove("hidden");
    } else {
        noResults.classList.add("hidden");
    }
}

// Modal functionality
function initPropertiesModal() {
    const addPropertyBtn = document.getElementById("addPropertyBtn");
    const addPropertyModal = document.getElementById("addPropertyModal");
    const closeModal = document.getElementById("closeModal");
    const cancelBtn = document.getElementById("cancelBtn");

    if (addPropertyBtn && addPropertyModal) {
        addPropertyBtn.addEventListener("click", () => {
            addPropertyModal.classList.remove("hidden");
            addPropertyModal.classList.add("flex");
            document.body.style.overflow = "hidden";
        });
    }

    function closeModalFunction() {
        addPropertyModal.classList.add("hidden");
        addPropertyModal.classList.remove("flex");
        document.body.style.overflow = "auto";
    }

    if (closeModal) {
        closeModal.addEventListener("click", closeModalFunction);
    }

    if (cancelBtn) {
        cancelBtn.addEventListener("click", closeModalFunction);
    }

    if (addPropertyModal) {
        addPropertyModal.addEventListener("click", (e) => {
            if (e.target === addPropertyModal) {
                closeModalFunction();
            }
        });
    }
}

// View Property Modal
async function openViewModal(propertyId) {
    try {
        const response = await fetch(`/landlord/properties/${propertyId}`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const property = await response.json();

        const modalContent = `
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">${property.property_name}</h2>
                            <p class="text-sm text-gray-500">Complete property information</p>
                        </div>
                    </div>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <!-- Property Image -->
                    <div class="mb-6">
                        <img src="/storage/${property.property_image}" alt="${property.property_name}" class="w-full h-64 object-cover rounded-lg">
                    </div>

                    <!-- Property Information Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Basic Information
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Property Name:</span>
                                    <span class="font-medium text-gray-900">${property.property_name}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium text-gray-900 capitalize">${property.property_type}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium ${property.status === "active" ? "bg-green-100 text-green-800" : "bg-gray-100 text-gray-800"} capitalize">
                                        ${property.status}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Address:</span>
                                    <span class="font-medium text-gray-900 text-right">${property.property_address}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Property Statistics -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                Property Statistics
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Units:</span>
                                    <span class="font-medium text-gray-900">${property.units_count || 0}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Occupied Units:</span>
                                    <span class="font-medium text-gray-900">${property.occupied_units || 0}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Occupancy Rate:</span>
                                    <span class="font-medium text-gray-900">
                                        ${property.units_count > 0 ? Math.round((property.occupied_units / property.units_count) * 100) : 0}%
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Smart Devices:</span>
                                    <span class="font-medium text-gray-900">${property.devices_count || 0}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>
                            Financial Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Property Value</div>
                                <div class="text-lg font-semibold text-gray-900">₱${parseFloat(property.property_price).toLocaleString()}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-clipboard text-blue-600 mr-2"></i>
                            Description
                        </h3>
                        <p class="text-gray-600 leading-relaxed">${property.property_description}</p>
                    </div>

                    <!-- Smart Devices Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-plug text-blue-600 mr-2"></i>
                            Smart Devices
                        </h3>
                        <div id="smartDevicesList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Smart devices will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById("viewPropertyModal").innerHTML = modalContent;
        document.getElementById("viewPropertyModal").classList.remove("hidden");
        document.getElementById("viewPropertyModal").classList.add("flex");
        document.body.style.overflow = "hidden";

        // Load smart devices
        await loadSmartDevices(propertyId);
    } catch (error) {
        console.error("Error loading property:", error);
        alert("Error loading property details. Please try again.");
    }
}

// Load smart devices for a property
async function loadSmartDevices(propertyId) {
    try {
        const response = await fetch(
            `/landlord/properties/${propertyId}/devices`,
        );

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const devices = await response.json();

        const devicesContainer = document.getElementById("smartDevicesList");
        if (devices.length === 0) {
            devicesContainer.innerHTML =
                '<p class="text-gray-500 col-span-2">No smart devices connected to this property.</p>';
            return;
        }

        devicesContainer.innerHTML = devices
            .map(
                (device) => `
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-gray-900">${device.device_name}</h4>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${device.connection_status === "online" ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800"}">
                        ${device.connection_status}
                    </span>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Type:</span>
                        <span class="capitalize">${device.device_type}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Power:</span>
                        <span class="capitalize ${device.power_status === "on" ? "text-green-600" : "text-red-600"}">${device.power_status}</span>
                    </div>
                    ${
                        device.battery_level
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
            .join("");
    } catch (error) {
        console.error("Error loading smart devices:", error);
        document.getElementById("smartDevicesList").innerHTML =
            '<p class="text-red-500 col-span-2">Error loading devices.</p>';
    }
}

function closeViewModal() {
    document.getElementById("viewPropertyModal").classList.add("hidden");
    document.getElementById("viewPropertyModal").classList.remove("flex");
    document.body.style.overflow = "auto";
}

function closeEditModal() {
    document.getElementById("editPropertyModal").classList.add("hidden");
    document.getElementById("editPropertyModal").classList.remove("flex");
    document.body.style.overflow = "auto";
}

// Initialize properties functionality
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");

    if (searchInput) {
        searchInput.addEventListener("input", filterProperties);
    }

    if (statusFilter) {
        statusFilter.addEventListener("change", filterProperties);
    }

    initPropertiesModal();
    console.log("Properties management system loaded");
});

// Edit Property Modal
async function openEditModal(propertyId) {
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
                            <span>New Device</span>
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
                                <p class="text-sm text-gray-400 mt-1">Click "New Device" to get started</p>
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
