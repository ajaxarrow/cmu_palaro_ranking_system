@extends('layouts.admin', ['title' => 'Teams'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
<style>
    .color-badge {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 5px;
        border: 1px solid #ddd;
    }
    .color-info {
        display: flex;
        align-items: center;
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }
</style>
@endsection
@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Display Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Teams</h4>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                Add Team
            </button>
        </div>

        <!-- Add Team Modal -->
        <div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('teams.store')}}" method="POST" onsubmit="processImageAndSubmit(event, this)" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="teamImage" class="form-label">Image</label>
                                <input required type="file" accept="image/*" name="image" class="form-control" id="teamImage" onchange="previewImageWithAnalysis(event, 'add')" required>
                                <div id="imagePreviewAdd" class="mt-2 text-center" style="display: none;">
                                    <img id="previewImageAdd" src="" width="100" height="100" class="rounded-circle object-fit-cover">
                                    <div id="colorInfoAdd" class="color-info mt-2"></div>
                                </div>
                                <input type="hidden" name="dominant_color" id="dominantColorAdd">
                            </div>

                            <div class="mb-3">
                                <label for="teamName" class="form-label">Name</label>
                                <input required type="text" name="name" class="form-control" id="teamName" placeholder="Enter Team Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="teamDescription" class="form-label">Description</label>
                                <input required type="text" name="desc" class="form-control" id="teamDescription" placeholder="Enter Team Description" required>
                            </div>
                            <div class="mb-3">
                                <label for="collegeSelectAdd" class="form-label">Select Colleges</label>
                                <select required name="college_ids[]" id="collegeSelectAdd" class="form-select" multiple required>
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple colleges.</small>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 checkbox-all" id="datatable_1">
                            <thead>
                                <tr>
                                    <th class="ps-0">Name</th>
                                    <th>Description</th>
                                    <th>Colleges</th>
                                    <th>Dominant Color</th> <!-- New column for displaying dominant color -->
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teams as $team)
                                    <tr>
                                        <td class="ps-0 d-flex align-items-center gap-1">
                                            @if($team->image)
                                                <img src="{{ asset('storage/' . $team->image) }}" width="40" height="40" class="rounded-circle object-cover" style="object-fit: cover; object-position: top;">
                                            @else
                                                <i class="ph-duotone fs-24 ph-ranking mb-0"></i>
                                            @endif
                                            <p class="d-inline-block align-middle mb-0 py-2">
                                                <span class="font-16 fw-medium ml-3">{{$team->name}}</span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $team->desc }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">
                                                @if ($team->colleges)
                                                    @foreach ($team->colleges as $college)
                                                        {{ $college->name }}@if (!$loop->last), @endif
                                                    @endforeach
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <!-- Display dominant color if available -->
                                            @if($team->dominant_color)
                                                <div class="color-info">
                                                    <span class="color-badge" style="background-color: {{ $team->dominant_color }}"></span>
                                                    <span>{{ $team->dominant_color }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Not analyzed</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" href="{{ route('teams.show', $team->id) }}">
                                                <i class="mdi mdi-eye-outline fs-14 text-primary"></i>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $team->id }}">
                                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $team->id }}">
                                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- EDIT MODAL for each team -->
                                    <div class="modal fade" id="editModal{{ $team->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $team->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $team->id }}">Edit Team</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('teams.update', $team->id) }}" method="POST" onsubmit="processImageAndSubmit(event, this)" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <div class="mb-2 d-flex justify-content-center align-items-center">
                                                                    <img id="imagePreview{{ $team->id }}" 
                                                                        src="{{ $team->image ? asset('storage/' . $team->image) : asset('storage/default.jpg') }}" 
                                                                        width="100" height="100" 
                                                                        class="rounded-circle object-fit-cover"
                                                                            style="border: 3px solid {{ $team->dominant_color ?? '#ddd' }};">
                                                                </div>
                                                                                                                        
                                                            <!-- Display current dominant color if available -->
                                                            @if($team->dominant_color)
                                                                <div id="currentColorInfo{{ $team->id }}" class="color-info text-center mb-2">
                                                                    <span class="color-badge" style="background-color: {{ $team->dominant_color }}"></span>
                                                                    <span>Current: {{ $team->dominant_color }}</span>
                                                                </div>
                                                            @endif
                                                            
                                                            <!-- Will show new color analysis -->
                                                            <div id="colorInfo{{ $team->id }}" class="color-info text-center mb-2" style="display: none;"></div>
                                                                                                                        
                                                            <label for="teamImage{{ $team->id }}" class="form-label">Image</label>
                                                            <input type="file" accept="image/*" name="image" class="form-control" 
                                                                id="teamImage{{ $team->id }}" 
                                                                onchange="previewImageWithAnalysis(event, '{{ $team->id }}')">
                                                            
                                                            <input type="hidden" name="dominant_color" id="dominantColor{{ $team->id }}" 
                                                                value="{{ $team->dominant_color ?? '' }}">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="editTeamName{{ $team->id }}" class="form-label">Name</label>
                                                            <input required type="text" name="name" class="form-control" placeholder="Enter Team Name" id="editTeamName{{ $team->id }}" value="{{ $team->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editTeamDescription{{ $team->id }}" class="form-label">Description</label>
                                                            <input required type="text" placeholder="Enter Team Description" name="desc" class="form-control" id="editTeamDescription{{ $team->id }}" value="{{ $team->desc }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="collegeSelectEdit{{ $team->id }}" class="form-label">Select Colleges</label>
                                                            @php
                                                                $selectedColleges = $team->colleges->pluck('id')->toArray();
                                                            @endphp

                                                            <select required name="college_ids[]" id="collegeSelectEdit{{ $team->id }}" class="form-select" multiple required>
                                                                @foreach ($colleges as $college)
                                                                    <option value="{{ $college->id }}" {{ in_array($college->id, $selectedColleges) ? 'selected' : '' }}>
                                                                        {{ $college->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple colleges.</small>
                                                        </div>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DELETE MODAL for each team -->
                                    <div class="modal fade" id="deleteModal{{ $team->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $team->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $team->id }}">Delete Team</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this team?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <form id="deleteForm{{ $team->id }}" action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;" onsubmit="disableSubmitButton(this)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
<script>
    function disableSubmitButton(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Disable the button
        submitButton.innerText = 'Submitting...'; // Optional: Change button text
    }

    // Function to analyze image colors and find dominant color
    // Function to analyze image colors and find dominant color
function analyzeImageColors(image) {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // Scale down for performance
        const maxDimension = 100;
        let width = image.naturalWidth;
        let height = image.naturalHeight;
        
        if (width > height && width > maxDimension) {
            height = Math.round(height * (maxDimension / width));
            width = maxDimension;
        } else if (height > maxDimension) {
            width = Math.round(width * (maxDimension / height));
            height = maxDimension;
        }
        
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(image, 0, 0, width, height);
        
        // Get pixel data
        const imageData = ctx.getImageData(0, 0, width, height).data;
        
        // Process colors
        const colorCounts = {};
        const whiteThreshold = 240; // Threshold to consider a color as white
        const blackThreshold = 30;  // Threshold to consider a color as black
        const grayThreshold = 30;   // Threshold to identify gray (low saturation)
        
        for (let i = 0; i < imageData.length; i += 4) {
            const r = imageData[i];
            const g = imageData[i + 1];
            const b = imageData[i + 2];
            
            // Skip pixels that are close to white
            if (r > whiteThreshold && g > whiteThreshold && b > whiteThreshold) {
                continue;
            }
            
            // Skip pixels that are close to black
            if (r < blackThreshold && g < blackThreshold && b < blackThreshold) {
                continue;
            }
            
            // Skip pixels that are close to gray
            const max = Math.max(r, g, b);
            const min = Math.min(r, g, b);
            const saturation = (max === 0) ? 0 : (max - min) / max * 100;
            
            if (saturation < grayThreshold) {
                continue;
            }
            
            // Quantize colors to reduce the number of unique colors
            const quantizedR = Math.round(r / 10) * 10;
            const quantizedG = Math.round(g / 10) * 10;
            const quantizedB = Math.round(b / 10) * 10;
            
            const colorKey = `${quantizedR},${quantizedG},${quantizedB}`;
            
            if (colorCounts[colorKey]) {
                colorCounts[colorKey].count++;
                colorCounts[colorKey].saturation = Math.max(colorCounts[colorKey].saturation, saturation);
            } else {
                colorCounts[colorKey] = {
                    r: quantizedR,
                    g: quantizedG,
                    b: quantizedB,
                    count: 1,
                    saturation: saturation
                };
            }
        }
        
        // Convert to array for sorting
        let colorsArray = Object.values(colorCounts);
        
        // If we don't have enough saturated colors, fall back to all colors
        if (colorsArray.length === 0) {
            // Process again without saturation filtering
            for (let i = 0; i < imageData.length; i += 4) {
                const r = imageData[i];
                const g = imageData[i + 1];
                const b = imageData[i + 2];
                
                // Still skip pure white pixels
                if (r > whiteThreshold && g > whiteThreshold && b > whiteThreshold) {
                    continue;
                }
                
                // Quantize colors
                const quantizedR = Math.round(r / 10) * 10;
                const quantizedG = Math.round(g / 10) * 10;
                const quantizedB = Math.round(b / 10) * 10;
                
                const colorKey = `${quantizedR},${quantizedG},${quantizedB}`;
                
                if (colorCounts[colorKey]) {
                    colorCounts[colorKey].count++;
                } else {
                    const max = Math.max(r, g, b);
                    const min = Math.min(r, g, b);
                    const saturation = (max === 0) ? 0 : (max - min) / max * 100;
                    
                    colorCounts[colorKey] = {
                        r: quantizedR,
                        g: quantizedG,
                        b: quantizedB,
                        count: 1,
                        saturation: saturation
                    };
                }
            }
            
            colorsArray = Object.values(colorCounts);
        }
        
        // Sort colors by count and saturation (prioritize more saturated colors)
        colorsArray.sort((a, b) => {
            // First prioritize colors with significant presence (at least 5% of non-white pixels)
            const aSignificant = a.count > (width * height * 0.05);
            const bSignificant = b.count > (width * height * 0.05);
            
            if (aSignificant && !bSignificant) return -1;
            if (!aSignificant && bSignificant) return 1;
            
            // For colors with significant presence, prefer more saturated ones
            if (aSignificant && bSignificant) {
                if (a.saturation > b.saturation) return -1;
                if (a.saturation < b.saturation) return 1;
            }
            
            // If saturation is similar or both are not significant, go by count
            return b.count - a.count;
        });
        
        // If we found colors, return the dominant one
        if (colorsArray.length > 0) {
            const dominantColor = colorsArray[0];
            const hexColor = rgbToHex(dominantColor.r, dominantColor.g, dominantColor.b);
            resolve({
                hex: hexColor,
                rgb: `rgb(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b})`,
                percentage: ((dominantColor.count / (width * height)) * 100).toFixed(2),
                saturation: dominantColor.saturation.toFixed(2)
            });
        } else {
            // No valid colors found (all white or filtered out)
            // Return a default color
            resolve({
                hex: '#4682B4', // Steel blue as fallback
                rgb: 'rgb(70, 130, 180)',
                percentage: '100',
                saturation: '0'
            });
        }
    });
}
    // Convert RGB to hex
    function rgbToHex(r, g, b) {
        return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
    }

    // Function to handle image preview with color analysis
    // Function to handle image preview with color analysis
function previewImageWithAnalysis(event, id) {
    const input = event.target;
    const prefix = id === 'add' ? 'Add' : id;
    const preview = document.getElementById(`imagePreview${prefix}`);
    const previewImage = document.getElementById(`previewImage${prefix}`) || document.getElementById(`imagePreview${prefix}`);
    const colorInfo = document.getElementById(`colorInfo${prefix}`);
    const dominantColorInput = document.getElementById(`dominantColor${prefix}`);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Set preview image
            previewImage.src = e.target.result;
            
            // Show preview container if it's the add form
            if (id === 'add') {
                preview.style.display = 'block';
            }
            
            // Create temp image to get natural dimensions
            const tempImg = new Image();
            tempImg.onload = function() {
                // Analyze colors when image is loaded
                analyzeImageColors(tempImg).then(colorData => {
                    if (colorData) {
                        // Display color info
                        colorInfo.innerHTML = `
                            <span class="color-badge" style="background-color: ${colorData.hex}"></span>
                            <span>Dominant: ${colorData.hex} (${colorData.percentage}%)</span>
                        `;
                        colorInfo.style.display = 'flex';
                        
                        // Store the dominant color in the hidden input
                        dominantColorInput.value = colorData.hex;
                        
                        // Apply the dominant color as border to the image preview
                        previewImage.style.border = `3px solid ${colorData.hex}`;
                        
                        // Hide current color info if it exists (for edit forms)
                        const currentColorInfo = document.getElementById(`currentColorInfo${prefix}`);
                        if (currentColorInfo) {
                            currentColorInfo.style.display = 'none';
                        }
                    }
                });
            };
            tempImg.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
    
    // Function to process the form submission
    function processImageAndSubmit(event, form) {
        const fileInput = form.querySelector('input[type="file"]');
        const dominantColorInput = form.querySelector('input[name="dominant_color"]');
        
        // If no new file is selected, just submit the form with existing color data
        if (!fileInput.files || !fileInput.files[0]) {
            disableSubmitButton(form);
            return true;
        }
        
        // If we already have analyzed the color (dominantColorInput has a value), just submit
        if (dominantColorInput.value) {
            disableSubmitButton(form);
            return true;
        }
        
        // Otherwise, prevent default submission and analyze the image first
        event.preventDefault();
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const tempImg = new Image();
            tempImg.onload = function() {
                analyzeImageColors(tempImg).then(colorData => {
                    if (colorData) {
                        dominantColorInput.value = colorData.hex;
                    }
                    // Now submit the form
                    disableSubmitButton(form);
                    form.submit();
                });
            };
            tempImg.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
        
        return false;
    }
</script>
@endsection

@section('script-bottom')
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
@vite(['resources/js/pages/crm-dashboard.init.js'])
@endsection