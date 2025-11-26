// device.js - Updated to avoid conflicts
// Add Device Modal
function openAddDeviceModal(propertyId) {
    const modalContent = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plug text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Add Smart Device</h2>
                            <p class="text-sm text-gray-500">Connect a new smart device to your property</p>
                        </div>
                    </div>
                    <button onclick="closeAddDeviceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="addDeviceForm" class="p-6 space-y-6">
                    <input type="hidden" name="prop_id" value="${propertyId}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Device Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="device_name" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Living Room Thermostat">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Device Type <span class="text-red-500">*</span>
                            </label>
                            <select name="device_type" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent device-type-select">
                                <option value="">Select Type</option>
                                <option value="thermostat">Thermostat</option>
                                <option value="camera">Security Camera</option>
                                <option value="lock">Smart Lock</option>
                                <option value="lights">Smart Lights</option>
                                <option value="sensor">Motion Sensor</option>
                                <option value="doorbell">Smart Doorbell</option>
                                <option value="plug">Smart Plug</option>
                                <option value="alarm">Alarm System</option>
                                <option value="blinds">Smart Blinds</option>
                                <option value="speaker">Smart Speaker</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Model
                            </label>
                            <input type="text" name="model"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Nest Thermostat 3rd Gen">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Serial Number
                            </label>
                            <input type="text" name="serial_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., SN123456789">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Connection Status <span class="text-red-500">*</span>
                            </label>
                            <select name="connection_status" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Power Status <span class="text-red-500">*</span>
                            </label>
                            <select name="power_status" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="on">On</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Battery Level (%)
                        </label>
                        <input type="number" name="battery_level" min="0" max="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="0-100">
                        <p class="mt-1 text-sm text-gray-500">Leave empty if device doesn't have a battery</p>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeAddDeviceModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                            Add Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;

    // Create modal container if it doesn't exist
    let modalContainer = document.getElementById("addDeviceModal");
    if (!modalContainer) {
        modalContainer = document.createElement("div");
        modalContainer.id = "addDeviceModal";
        document.body.appendChild(modalContainer);
    }

    modalContainer.innerHTML = modalContent;

    // Add smart defaults for device types
    const deviceTypeSelect = modalContainer.querySelector(
        ".device-type-select",
    );
    deviceTypeSelect.addEventListener("change", function () {
        setDeviceSmartDefaults(this);
    });

    // Add form submit handler
    document
        .getElementById("addDeviceForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            addSmartDevice(propertyId);
        });
}

function closeAddDeviceModal() {
    const modalContainer = document.getElementById("addDeviceModal");
    if (modalContainer) {
        modalContainer.remove();
    }
}

// Set smart defaults based on device type
function setDeviceSmartDefaults(selectElement) {
    const form = selectElement.closest("form");
    const deviceType = selectElement.value;
    const deviceNameInput = form.querySelector('input[name="device_name"]');
    const modelInput = form.querySelector('input[name="model"]');

    // Set smart defaults based on device type
    const defaults = {
        thermostat: {
            name: "Smart Thermostat",
            model: "Nest Learning Thermostat",
        },
        camera: { name: "Security Camera", model: "Arlo Pro 4" },
        lock: { name: "Smart Lock", model: "August Smart Lock" },
        lights: { name: "Smart Lights", model: "Philips Hue" },
        sensor: { name: "Motion Sensor", model: "Ring Motion Sensor" },
        doorbell: { name: "Smart Doorbell", model: "Ring Video Doorbell" },
        plug: { name: "Smart Plug", model: "TP-Link Kasa" },
        alarm: { name: "Alarm System", model: "SimpliSafe" },
        blinds: { name: "Smart Blinds", model: "IKEA Smart Blinds" },
        speaker: { name: "Smart Speaker", model: "Amazon Echo" },
    };

    if (defaults[deviceType]) {
        if (!deviceNameInput.value) {
            deviceNameInput.value = defaults[deviceType].name;
        }
        if (!modelInput.value) {
            modelInput.value = defaults[deviceType].model;
        }
    }
}

// Add Device Function
async function addSmartDevice(propertyId) {
    try {
        const form = document.getElementById("addDeviceForm");
        const formData = new FormData(form);

        const deviceData = {
            device_name: formData.get("device_name"),
            device_type: formData.get("device_type"),
            model: formData.get("model"),
            serial_number: formData.get("serial_number"),
            connection_status: formData.get("connection_status"),
            power_status: formData.get("power_status"),
            battery_level: formData.get("battery_level")
                ? parseInt(formData.get("battery_level"))
                : null,
        };

        console.log("Sending device data:", deviceData);

        const response = await fetch(
            "/landlord/properties/" + propertyId + "/devices",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: JSON.stringify(deviceData),
            },
        );

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to add device");
        }

        if (!result.success) {
            throw new Error(result.message || "Failed to add device");
        }

        // Close modal and refresh devices list
        closeAddDeviceModal();
        refreshDevicesList(propertyId);

        // Show success message
        alert("Device added successfully!");
    } catch (error) {
        console.error("Error adding device:", error);
        alert("Error adding device: " + error.message);
    }
}

// Edit Device Modal
async function editSmartDevice(deviceId) {
    try {
        const response = await fetch(`/landlord/devices/${deviceId}/edit`);

        if (!response.ok) {
            throw new Error("Failed to fetch device data");
        }

        const device = await response.json();

        const modalContent = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Edit Smart Device</h2>
                                <p class="text-sm text-gray-500">Update device information</p>
                            </div>
                        </div>
                        <button onclick="closeEditDeviceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="editDeviceForm" class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Device Name</label>
                                <input type="text" name="device_name" value="${device.device_name}" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Device Type</label>
                                <select name="device_type" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="thermostat" ${device.device_type === "thermostat" ? "selected" : ""}>Thermostat</option>
                                    <option value="camera" ${device.device_type === "camera" ? "selected" : ""}>Security Camera</option>
                                    <option value="lock" ${device.device_type === "lock" ? "selected" : ""}>Smart Lock</option>
                                    <option value="lights" ${device.device_type === "lights" ? "selected" : ""}>Smart Lights</option>
                                    <option value="sensor" ${device.device_type === "sensor" ? "selected" : ""}>Motion Sensor</option>
                                    <option value="doorbell" ${device.device_type === "doorbell" ? "selected" : ""}>Smart Doorbell</option>
                                    <option value="plug" ${device.device_type === "plug" ? "selected" : ""}>Smart Plug</option>
                                    <option value="alarm" ${device.device_type === "alarm" ? "selected" : ""}>Alarm System</option>
                                    <option value="blinds" ${device.device_type === "blinds" ? "selected" : ""}>Smart Blinds</option>
                                    <option value="speaker" ${device.device_type === "speaker" ? "selected" : ""}>Smart Speaker</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                                <input type="text" name="model" value="${device.model || ""}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                                <input type="text" name="serial_number" value="${device.serial_number || ""}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Connection Status</label>
                                <select name="connection_status" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="online" ${device.connection_status === "online" ? "selected" : ""}>Online</option>
                                    <option value="offline" ${device.connection_status === "offline" ? "selected" : ""}>Offline</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Power Status</label>
                                <select name="power_status" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="on" ${device.power_status === "on" ? "selected" : ""}>On</option>
                                    <option value="off" ${device.power_status === "off" ? "selected" : ""}>Off</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Battery Level (%)</label>
                            <input type="number" name="battery_level" value="${device.battery_level || ""}" min="0" max="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Leave empty if device doesn't have a battery</p>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeEditDeviceModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Update Device
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        // Create modal container if it doesn't exist
        let modalContainer = document.getElementById("editDeviceModal");
        if (!modalContainer) {
            modalContainer = document.createElement("div");
            modalContainer.id = "editDeviceModal";
            document.body.appendChild(modalContainer);
        }

        modalContainer.innerHTML = modalContent;

        // Add form submit handler
        document
            .getElementById("editDeviceForm")
            .addEventListener("submit", function (e) {
                e.preventDefault();
                updateSmartDevice(deviceId);
            });
    } catch (error) {
        console.error("Error loading device for edit:", error);
        alert("Error loading device for editing: " + error.message);
    }
}

function closeEditDeviceModal() {
    const modalContainer = document.getElementById("editDeviceModal");
    if (modalContainer) {
        modalContainer.remove();
    }
}

// Update Device Function
async function updateSmartDevice(deviceId) {
    try {
        const form = document.getElementById("editDeviceForm");
        const formData = new FormData(form);

        const deviceData = {
            device_name: formData.get("device_name"),
            device_type: formData.get("device_type"),
            model: formData.get("model"),
            serial_number: formData.get("serial_number"),
            connection_status: formData.get("connection_status"),
            power_status: formData.get("power_status"),
            battery_level: formData.get("battery_level")
                ? parseInt(formData.get("battery_level"))
                : null,
        };

        const response = await fetch("/landlord/devices/" + deviceId, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
            body: JSON.stringify(deviceData),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to update device");
        }

        if (!result.success) {
            throw new Error(result.message || "Failed to update device");
        }

        // Close modal and refresh devices list
        closeEditDeviceModal();

        // Refresh the parent property edit modal
        const propertyId = document.querySelector(
            'input[name="prop_id"]',
        )?.value;
        if (propertyId) {
            refreshDevicesList(propertyId);
        }

        alert("Device updated successfully!");
    } catch (error) {
        console.error("Error updating device:", error);
        alert("Error updating device: " + error.message);
    }
}

// Delete Device Function
async function deleteSmartDevice(deviceId) {
    if (
        !confirm(
            "Are you sure you want to delete this device? This action cannot be undone.",
        )
    ) {
        return;
    }

    try {
        const response = await fetch("/landlord/devices/" + deviceId, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                Accept: "application/json",
            },
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to delete device");
        }

        if (!result.success) {
            throw new Error(result.message || "Failed to delete device");
        }

        // Refresh devices list
        const propertyId = document.querySelector(
            'input[name="prop_id"]',
        )?.value;
        if (propertyId) {
            refreshDevicesList(propertyId);
        }

        alert("Device deleted successfully!");
    } catch (error) {
        console.error("Error deleting device:", error);
        alert("Error deleting device: " + error.message);
    }
}

// Archive Device Function
async function archiveDevice(deviceId) {
    if (
        !confirm(
            "Are you sure you want to archive this device? The device will be marked as archived and hidden from active listings.",
        )
    ) {
        return;
    }

    try {
        const response = await fetch(
            "/landlord/devices/" + deviceId + "/archive",
            {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
            },
        );

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || "Failed to archive device");
        }

        if (!result.success) {
            throw new Error(result.message || "Failed to archive device");
        }

        // Refresh devices list
        const propertyId = document.querySelector(
            'input[name="prop_id"]',
        )?.value;
        if (propertyId) {
            refreshDevicesList(propertyId);
        }

        alert("Device archived successfully!");
    } catch (error) {
        console.error("Error archiving device:", error);
        alert("Error archiving device: " + error.message);
    }
}

// Refresh Devices List
async function refreshDevicesList(propertyId) {
    try {
        const response = await fetch(
            `/landlord/properties/${propertyId}/devices`,
        );

        if (!response.ok) {
            throw new Error("Failed to fetch devices");
        }

        const devices = await response.json();
        const devicesContainer = document.getElementById("editDevicesList");

        if (!devicesContainer) return;

        if (devices.length === 0) {
            devicesContainer.innerHTML = `
                <div class="col-span-2 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-plug text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500">No smart devices connected</p>
                    <p class="text-sm text-gray-400 mt-1">Click "Add Device" to get started</p>
                </div>
            `;
            return;
        }

        devicesContainer.innerHTML = devices
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
            .join("");
    } catch (error) {
        console.error("Error refreshing devices list:", error);
    }
}

// Smart Devices Management for Add Property Form
document.addEventListener("DOMContentLoaded", function () {
    let deviceCount = 0;
    const devicesContainer = document.getElementById("devicesContainer");
    const noDevicesMessage = document.getElementById("noDevicesMessage");
    const addDeviceBtn = document.getElementById("addDeviceBtn");
    const deviceTemplate = document.getElementById("deviceTemplate");

    // Add device button click handler
    if (addDeviceBtn) {
        addDeviceBtn.addEventListener("click", function () {
            addPropertyFormDevice();
        });
    }

    function addPropertyFormDevice() {
        const template = deviceTemplate.content.cloneNode(true);
        const deviceElement = template.querySelector(".device-item");

        // Replace INDEX with actual device count
        const html = deviceElement.outerHTML.replace(/INDEX/g, deviceCount);
        const newDevice = document.createElement("div");
        newDevice.innerHTML = html;

        devicesContainer.appendChild(newDevice);
        deviceCount++;

        // Hide no devices message
        noDevicesMessage.style.display = "none";

        // Add remove event listener
        const removeBtn = newDevice.querySelector(".remove-device");
        removeBtn.addEventListener("click", function () {
            newDevice.remove();
            deviceCount--;

            // Show no devices message if no devices left
            if (deviceCount === 0) {
                noDevicesMessage.style.display = "block";
            }

            updateDeviceIndexes();
        });

        // Add device type change listener for smart defaults
        const deviceTypeSelect = newDevice.querySelector(".device-type-select");
        deviceTypeSelect.addEventListener("change", function () {
            setSmartDefaults(this);
        });
    }

    function updateDeviceIndexes() {
        const deviceItems = devicesContainer.querySelectorAll(".device-item");
        deviceItems.forEach((item, index) => {
            const inputs = item.querySelectorAll("input, select");
            inputs.forEach((input) => {
                const name = input.getAttribute("name");
                if (name) {
                    input.setAttribute(
                        "name",
                        name.replace(/devices\[\d+\]/, `devices[${index}]`),
                    );
                }
            });
        });
    }

    function setSmartDefaults(selectElement) {
        const deviceItem = selectElement.closest(".device-item");
        const deviceType = selectElement.value;
        const deviceNameInput = deviceItem.querySelector(
            'input[name*="device_name"]',
        );
        const modelInput = deviceItem.querySelector('input[name*="model"]');

        // Set smart defaults based on device type
        const defaults = {
            thermostat: {
                name: "Smart Thermostat",
                model: "Nest Learning Thermostat",
            },
            camera: { name: "Security Camera", model: "Arlo Pro 4" },
            lock: { name: "Smart Lock", model: "August Smart Lock" },
            lights: { name: "Smart Lights", model: "Philips Hue" },
            sensor: { name: "Motion Sensor", model: "Ring Motion Sensor" },
            doorbell: { name: "Smart Doorbell", model: "Ring Video Doorbell" },
            plug: { name: "Smart Plug", model: "TP-Link Kasa" },
            alarm: { name: "Alarm System", model: "SimpliSafe" },
            blinds: { name: "Smart Blinds", model: "IKEA Smart Blinds" },
            speaker: { name: "Smart Speaker", model: "Amazon Echo" },
        };

        if (defaults[deviceType]) {
            if (!deviceNameInput.value) {
                deviceNameInput.value = defaults[deviceType].name;
            }
            if (!modelInput.value) {
                modelInput.value = defaults[deviceType].model;
            }
        }
    }

    // Form submission handler
    const addPropertyForm = document.getElementById("addPropertyForm");
    if (addPropertyForm) {
        addPropertyForm.addEventListener("submit", function (e) {
            console.log("Submitting form with", deviceCount, "devices");
        });
    }
});
