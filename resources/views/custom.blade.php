<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Custom a Cookie</title>
    <style>
        .cookie-option {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .cookie-option:hover {
            transform: scale(1.05);
        }

        .cookie-option.selected {
            border: 2px solid #A4B38C;
            border-radius: 8px;
        }

        .btn-next {
            background-color: #A4B38C;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-next:hover {
            background-color: #8a9974;
        }

        .btn-back {
            background-color: #EFE5D9;
            color: #783F12;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 20px;
            margin-right: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #783F12;
        }

        .btn-back:hover {
            background-color: #e0d5c9;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .button-group {
            display: flex;
        }

        /* Preview styling */
        #cookie-preview {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        #shape-preview,
        #color-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        #color-preview {
            mix-blend-mode: multiply;
        }
    </style>
</head>

<body>
    <div class="h-screen w-screen bg-[#FAF5F2] p-10 flex">
        <!-- Left Panel - Options -->
        <div class="w-1/3 px-10 py-10">
            <a href="/">
                <div class="flex items-center gap-3 pb-10">
                    <h1 class="text-4xl font-bold text-[#783F12]">Dough</h1>
                    <img src="{{ asset('images/remi.png') }}" alt="douremi" class="w-15">
                </div>
            </a>

            <h1 class="text-xl font-bold text-[#A4B38C] mb-6" id="step-title">Choose a cookie shape:</h1>

            @php
                $cookieShapes = [
                    ['img' => 'circle.png', 'name' => 'Circle'],
                    ['img' => 'heart.png', 'name' => 'Heart'],
                ];

                $circleCookies = [
                    ['img' => 'circle_basic.png', 'name' => 'Basic'],
                    ['img' => 'circle_red.png', 'name' => 'Red'],
                    ['img' => 'circle_yellow.png', 'name' => 'Yellow'],
                    ['img' => 'circle_green.png', 'name' => 'Green'],
                    ['img' => 'circle_blue.png', 'name' => 'Blue'],
                    ['img' => 'circle_purple.png', 'name' => 'Purple'],
                    ['img' => 'circle_pink.png', 'name' => 'Pink'],
                    ['img' => 'circle_white.png', 'name' => 'White'],
                    ['img' => 'circle_orange.png', 'name' => 'Orange'],
                    ['img' => 'circle_brown.png', 'name' => 'Brown'],
                ];

                $heartCookies = [
                    ['img' => 'heart_basic.png', 'name' => 'Basic'],
                    ['img' => 'heart_red.png', 'name' => 'Red'],
                    ['img' => 'heart_yellow.png', 'name' => 'Yellow'],
                    ['img' => 'heart_green.png', 'name' => 'Green'],
                    ['img' => 'heart_blue.png', 'name' => 'Blue'],
                    ['img' => 'heart_purple.png', 'name' => 'Purple'],
                    ['img' => 'heart_pink.png', 'name' => 'Pink'],
                    ['img' => 'heart_white.png', 'name' => 'White'],
                    ['img' => 'heart_orange.png', 'name' => 'Orange'],
                    ['img' => 'heart_brown.png', 'name' => 'Brown'],
                ];
            @endphp

            <!-- Step 1: Shape Selection -->
            <div class="step active" id="step-shape">
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-[#783F12] mb-4">Shape:</h2>
                    <div class="flex flex-wrap gap-4" id="shape-options">
                        @foreach ($cookieShapes as $index => $cookieShape)
                            <div class="cookie-option shape-option p-2 {{ $index === 0 ? 'selected' : '' }}"
                                data-shape="{{ $cookieShape['name'] }}"
                                data-img="{{ asset('images/' . $cookieShape['img']) }}">
                                <img src="{{ asset('images/' . $cookieShape['img']) }}" alt="{{ $cookieShape['name'] }}"
                                    class="w-20 h-20 object-contain">
                                <p class="text-center mt-2 text-[#783F12]">{{ $cookieShape['name'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="button-group">
                    <button class="btn-next" id="btn-next-shape">Next</button>
                </div>
            </div>

            <!-- Step 2: Color Selection -->
            <div class="step" id="step-color">
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-[#783F12] mb-4">Color:</h2>
                    <div class="flex flex-wrap gap-4" id="color-options">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
                <div class="button-group">
                    <button class="btn-back" id="btn-back-color">Back</button>
                    <button class="btn-next" id="btn-next-color">Next</button>
                </div>
            </div>
        </div>

        <!-- Right Panel - Display -->
        <div class="w-2/3 h-auto bg-[#EFE5D9] rounded-[35px] flex items-center justify-center p-4">
            <div id="main-cookie-display" class="text-center">
                <div id="cookie-preview">
                    <img src="{{ asset('images/' . $cookieShapes[0]['img']) }}" alt="Shape Preview" id="shape-preview">
                    <img src="{{ asset('images/' . $circleCookies[0]['img']) }}" alt="Color Preview" id="color-preview">
                </div>
                <p class="text-[#783F12] mt-4 text-xl" id="cookie-description">Circle - Basic</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Convert PHP arrays to JavaScript
            const cookieOptions = {
                shapes: @json($cookieShapes),
                circle: @json($circleCookies),
                heart: @json($heartCookies)
            };

            // DOM Elements
            const stepTitle = document.getElementById('step-title');
            const stepShape = document.getElementById('step-shape');
            const stepColor = document.getElementById('step-color');
            const btnNextShape = document.getElementById('btn-next-shape');
            const btnNextColor = document.getElementById('btn-next-color');
            const btnBackColor = document.getElementById('btn-back-color');
            const shapeOptions = document.querySelectorAll('.shape-option');
            const colorOptions = document.getElementById('color-options');
            const shapePreview = document.getElementById('shape-preview');
            const colorPreview = document.getElementById('color-preview');
            const cookieDescription = document.getElementById('cookie-description');

            // Current selection
            let currentShape = 'Circle';
            let currentColor = 'Basic';

            // Initialize with default shape
            updateColorOptions('Circle');

            // Shape selection handler
            shapeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Update selected state
                    shapeOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    // Get selected shape
                    currentShape = this.dataset.shape;
                    const shapeImg = this.dataset.img;

                    // Update preview
                    shapePreview.src = shapeImg;
                    cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                });
            });

            // Next button for shape selection
            btnNextShape.addEventListener('click', function() {
                stepShape.classList.remove('active');
                stepColor.classList.add('active');
                stepTitle.textContent = `Choose a color for your ${currentShape} cookie:`;
                updateColorOptions(currentShape);
            });

            // Back button for color selection
            btnBackColor.addEventListener('click', function() {
                stepColor.classList.remove('active');
                stepShape.classList.add('active');
                stepTitle.textContent = `Choose a cookie shape:`;
            });

            // Update color options based on selected shape
            function updateColorOptions(shape) {
                colorOptions.innerHTML = '';
                const colors = shape === 'Circle' ? cookieOptions.circle : cookieOptions.heart;

                colors.forEach((color, index) => {
                    const colorDiv = document.createElement('div');
                    colorDiv.className = `cookie-option color-option p-2 ${index === 0 ? 'selected' : ''}`;
                    colorDiv.dataset.color = color.name;
                    colorDiv.dataset.img = `{{ asset('images/') }}/${color.img}`;

                    colorDiv.innerHTML = `
                        <img src="{{ asset('images/') }}/${color.img}" 
                             alt="${color.name}" 
                             class="w-20 h-20 object-contain">
                        <p class="text-center mt-2 text-[#783F12]">${color.name}</p>
                    `;

                    colorDiv.addEventListener('click', function() {
                        document.querySelectorAll('.color-option').forEach(opt => opt.classList
                            .remove('selected'));
                        this.classList.add('selected');

                        currentColor = this.dataset.color;
                        colorPreview.src = this.dataset.img;
                        cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                    });

                    colorOptions.appendChild(colorDiv);
                });

                // Reset to first color
                if (colors.length > 0) {
                    currentColor = colors[0].name;
                    colorPreview.src = `{{ asset('images/') }}/${colors[0].img}`;
                    cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                }
            }

            // Next button for color selection
            btnNextColor.addEventListener('click', function() {
                alert(`Your ${currentShape} ${currentColor} cookie has been selected!`);
                // Proceed to next step (e.g., toppings, packaging)
            });
        });
    </script>
</body>

</html>
