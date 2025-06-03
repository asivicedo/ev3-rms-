<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Part</h2>
            <button type="button" class="close-btn" onclick="closeModal('addModal')">&times;</button>
        </div>
        
        <div id="addAlert" style="display: none;"></div>
        
        <form id="addForm" onsubmit="handleAddSubmit(event)">
            <div class="form-group">
                <label for="add_part_name" class="form-label">Part Name</label>
                <input type="text" id="add_part_name" name="part_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="add_part_type" class="form-label">Part Type</label>
                <input type="text" id="add_part_type" name="part_type" class="form-control">
                <div class="form-text">E.g., Motor, Sensor, Beam, Wheel, etc.</div>
            </div>
            
            <div class="form-group">
                <label for="add_quantity" class="form-label">Quantity</label>
                <input type="number" id="add_quantity" name="quantity" class="form-control" min="0" required>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Add Part</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="importModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Import Parts from CSV</h2>
            <button type="button" class="close-btn" onclick="closeModal('importModal')">&times;</button>
        </div>
        
        <div id="importAlert" style="display: none;"></div>
        
        <form id="importForm" onsubmit="handleImportSubmit(event)" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csv_file" class="form-label">Choose CSV File</label>
                <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv" required>
                <div class="form-text">CSV columns must be, in order: Part Name, Part Type, Quantity.</div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Upload & Import</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('importModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function handleAddSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch('add_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const alertDiv = document.getElementById('addAlert');
        if (data.success) {
            alertDiv.className = 'alert alert-success';
            alertDiv.innerHTML = data.message;
            alertDiv.style.display = 'block';
            document.getElementById('addForm').reset();
            setTimeout(() => {
                closeModal('addModal');
                location.reload();
            }, 1500);
        } else {
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = data.message;
            alertDiv.style.display = 'block';
        }
    })
    .catch(error => {
        const alertDiv = document.getElementById('addAlert');
        alertDiv.className = 'alert alert-danger';
        alertDiv.innerHTML = 'An error occurred. Please try again.';
        alertDiv.style.display = 'block';
    });
}

function handleImportSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch('import_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const alertDiv = document.getElementById('importAlert');
        if (data.success) {
            alertDiv.className = 'alert alert-success';
            alertDiv.innerHTML = data.message;
            alertDiv.style.display = 'block';
            document.getElementById('importForm').reset();
            setTimeout(() => {
                closeModal('importModal');
                location.reload();
            }, 1500);
        } else {
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = data.message;
            alertDiv.style.display = 'block';
        }
    })
    .catch(error => {
        const alertDiv = document.getElementById('importAlert');
        alertDiv.className = 'alert alert-danger';
        alertDiv.innerHTML = 'An error occurred. Please try again.';
        alertDiv.style.display = 'block';
    });
}
</script>