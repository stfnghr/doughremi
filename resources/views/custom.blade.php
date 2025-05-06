<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Custom a Cookie</title>
    <style>
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
            width: 300px;
            height: 300px;
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

        /* Options preview styling */
        .options-preview {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .option-thumbnail {
            width: 40px;
            height: 40px;
            object-fit: contain;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: all 0.2s ease;
        }

        .option-thumbnail.selected {
            border-color: #A4B38C;
        }

        .option-thumbnail:hover {
            transform: scale(1.1);
        }

        #shape-options-preview,
        #color-options-preview {
            display: none;
        }

        #shape-options-preview.active,
        #color-options-preview.active {
            display: flex;
        }
    </style>
</head>

<body>
    <div class="h-screen w-screen bg-[#FAF5F2] p-10 flex">
        <!-- Left Panel - Instructions -->
        <div class="w-1/3 px-10 py-10">
            <a href="/">
                <div class="flex items-center gap-3 pb-10">
                    <h1 class="text-4xl font-bold text-[#783F12]">Dough</h1>
                    <img src="{{ asset('images/remi.png') }}" alt="douremi" class="w-15">
                </div>
            </a>

            <!-- Step 1: Shape Selection -->
            <div class="step active" id="step-shape">
                <h1 class="text-xl font-bold text-[#A4B38C] mb-6">Choose a cookie shape:</h1>
                <p class="text-[#783F12] mb-8">Select your preferred cookie shape from the options in the preview area.
                </p>
                <div class="button-group">
                    <button class="btn-next" id="btn-next-shape">Next</button>
                </div>
            </div>

            <!-- Step 2: Color Selection -->
            <div class="step" id="step-color">
                <h1 class="text-xl font-bold text-[#A4B38C] mb-6">Choose a cookie color:</h1>
                <p class="text-[#783F12] mb-8">Select your preferred cookie color from the options in the preview area.
                </p>
                <div class="button-group">
                    <button class="btn-back" id="btn-back-color">Back</button>
                    <button class="btn-next" id="btn-next-color">Next</button>
                </div>
            </div>
        </div>

        <!-- Right Panel - Display with Selection Options -->
        <div class="w-2/3 h-auto bg-[#EFE5D9] rounded-[35px] flex items-center justify-center p-4 relative">
            <div id="main-cookie-display" class="text-center">
                <div id="cookie-preview">
                    <img src="{{ asset('images/circle.png') }}" alt="Shape Preview" id="shape-preview">
                    <img src="{{ asset('images/circle_basic.png') }}" alt="Color Preview" id="color-preview">
                </div>
                <p class="text-[#783F12] mt-4 text-xl" id="cookie-description">Circle - Basic</p>
            </div>

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

            <!-- Shape options preview -->
            <div class="options-preview active" id="shape-options-preview">
                @foreach ($cookieShapes as $index => $cookieShape)
                    <img src="{{ asset('images/' . $cookieShape['img']) }}" alt="{{ $cookieShape['name'] }}"
                        class="option-thumbnail {{ $index === 0 ? 'selected' : '' }}"
                        data-shape="{{ $cookieShape['name'] }}" data-img="{{ asset('images/' . $cookieShape['img']) }}">
                @endforeach
            </div>

            <!-- Color options preview -->
            <div class="options-preview" id="color-options-preview">
                @foreach ($circleCookies as $index => $color)
                    <img src="{{ asset('images/' . $color['img']) }}" alt="{{ $color['name'] }}"
                        class="option-thumbnail {{ $index === 0 ? 'selected' : '' }}"
                        data-color="{{ $color['name'] }}" data-img="{{ asset('images/' . $color['img']) }}">
                @endforeach
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
            const stepShape = document.getElementById('step-shape');
            const stepColor = document.getElementById('step-color');
            const btnNextShape = document.getElementById('btn-next-shape');
            const btnNextColor = document.getElementById('btn-next-color');
            const btnBackColor = document.getElementById('btn-back-color');
            const shapePreview = document.getElementById('shape-preview');
            const colorPreview = document.getElementById('color-preview');
            const cookieDescription = document.getElementById('cookie-description');
            const shapeOptionsPreview = document.getElementById('shape-options-preview');
            const colorOptionsPreview = document.getElementById('color-options-preview');

            // Current selection
            let currentShape = 'Circle';
            let currentColor = 'Basic';
            let currentShapeImg = "{{ asset('images/circle.png') }}";
            let currentColorImg = "{{ asset('images/circle_basic.png') }}";

            // Initialize with default shape
            updateColorOptions('Circle');

            // Shape selection handler
            document.querySelectorAll('#shape-options-preview .option-thumbnail').forEach(option => {
                option.addEventListener('click', function() {
                    // Update selected state
                    document.querySelectorAll('#shape-options-preview .option-thumbnail').forEach(
                        thumb => {
                            thumb.classList.remove('selected');
                        });
                    this.classList.add('selected');

                    // Get selected shape
                    currentShape = this.dataset.shape;
                    currentShapeImg = this.dataset.img;

                    // Update preview
                    shapePreview.src = currentShapeImg;
                    cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                });
            });

            // Next button for shape selection
            btnNextShape.addEventListener('click', function() {
                stepShape.classList.remove('active');
                stepColor.classList.add('active');
                shapeOptionsPreview.classList.remove('active');
                colorOptionsPreview.classList.add('active');
                updateColorOptions(currentShape);
            });

            // Back button for color selection
            btnBackColor.addEventListener('click', function() {
                stepColor.classList.remove('active');
                stepShape.classList.add('active');
                colorOptionsPreview.classList.remove('active');
                shapeOptionsPreview.classList.add('active');
            });

            // Color selection handler
            document.querySelectorAll('#color-options-preview .option-thumbnail').forEach(option => {
                option.addEventListener('click', function() {
                    // Update selected state
                    document.querySelectorAll('#color-options-preview .option-thumbnail').forEach(
                        thumb => {
                            thumb.classList.remove('selected');
                        });
                    this.classList.add('selected');

                    // Get selected color
                    currentColor = this.dataset.color;
                    currentColorImg = this.dataset.img;

                    // Update preview
                    colorPreview.src = currentColorImg;
                    cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                });
            });

            // Update color options based on selected shape
            function updateColorOptions(shape) {
                colorOptionsPreview.innerHTML = '';
                const colors = shape === 'Circle' ? cookieOptions.circle : cookieOptions.heart;

                colors.forEach((color, index) => {
                    const colorThumb = document.createElement('img');
                    colorThumb.src = `{{ asset('images/') }}/${color.img}`;
                    colorThumb.alt = color.name;
                    colorThumb.className = `option-thumbnail ${index === 0 ? 'selected' : ''}`;
                    colorThumb.dataset.color = color.name;
                    colorThumb.dataset.img = `{{ asset('images/') }}/${color.img}`;

                    colorThumb.addEventListener('click', function() {
                        document.querySelectorAll('#color-options-preview .option-thumbnail')
                            .forEach(thumb => {
                                thumb.classList.remove('selected');
                            });
                        this.classList.add('selected');

                        currentColor = this.dataset.color;
                        currentColorImg = this.dataset.img;
                        colorPreview.src = currentColorImg;
                        cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                    });

                    colorOptionsPreview.appendChild(colorThumb);
                });

                // Reset to first color
                if (colors.length > 0) {
                    currentColor = colors[0].name;
                    currentColorImg = `{{ asset('images/') }}/${colors[0].img}`;
                    colorPreview.src = currentColorImg;
                    cookieDescription.textContent = `${currentShape} - ${currentColor}`;
                }
            }

            // Next button for color selection - redirect to confirm page
            btnNextColor.addEventListener('click', function() {
                // Create a form to submit the data
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('order.confirm') }}";

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);

                // Add shape data
                const shapeInput = document.createElement('input');
                shapeInput.type = 'hidden';
                shapeInput.name = 'shape';
                shapeInput.value = currentShape;
                form.appendChild(shapeInput);

                // Add color data
                const colorInput = document.createElement('input');
                colorInput.type = 'hidden';
                colorInput.name = 'color';
                colorInput.value = currentColor;
                form.appendChild(colorInput);

                // Add shape image path
                const shapeImgInput = document.createElement('input');
                shapeImgInput.type = 'hidden';
                shapeImgInput.name = 'shape_img';
                shapeImgInput.value = currentShapeImg;
                form.appendChild(shapeImgInput);

                // Add color image path
                const colorImgInput = document.createElement('input');
                colorImgInput.type = 'hidden';
                colorImgInput.name = 'color_img';
                colorImgInput.value = currentColorImg;
                form.appendChild(colorImgInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
</body>

</html>
