<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Quicksand:wght@400;500;700&display=swap"
        rel="stylesheet">
    <title>Custom a Cookie</title>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #FAF5F2;
            /* Default background */
            color: #783F12;
            /* Default text color */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .font-coiny {
            font-family: 'Coiny', cursive;
        }

        .content-wrapper {
            flex-grow: 1;
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
            width: 300px;
            height: 300px;
            margin: 0 auto;
        }

        #shape-preview,
        #color-preview,
        #topping-preview-img {
            /* Renamed for clarity */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        #color-preview {
            /* mix-blend-mode: multiply; */
        }

        #topping-preview-img {
            /* mix-blend-mode: overlay; */
            /* Experiment with blend modes for toppings */
            /* opacity: 0.8; */
            /* Optional: make toppings slightly transparent */
        }


        /* Options preview styling */
        .options-preview {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-wrap: wrap;
            /* Allow wrapping if many options */
            gap: 10px;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            /* Prevent it from becoming too wide */
        }

        .option-thumbnail {
            width: 40px;
            height: 40px;
            object-fit: contain;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: all 0.2s ease;
            background-color: #fff;
            /* Add a slight background to see transparent PNGs better */
        }

        .option-thumbnail.selected {
            border-color: #A4B38C;
        }

        .option-thumbnail:hover {
            transform: scale(1.1);
        }

        #shape-options-preview,
        #color-options-preview,
        #topping-options-preview {
            display: none;
        }

        #shape-options-preview.active,
        #color-options-preview.active,
        #topping-options-preview.active {
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
                    <h1 class="text-4xl font-coiny text-[#783F12]">Dough</h1>
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

            <!-- Step 3: Topping Selection -->
            <div class="step" id="step-topping">
                <h1 class="text-xl font-bold text-[#A4B38C] mb-6">Choose your toppings:</h1>
                <p class="text-[#783F12] mb-8">Select your desired toppings from the options in the preview area.
                </p>
                <div class="button-group">
                    <button class="btn-back" id="btn-back-topping">Back</button>
                    <button class="btn-next" id="btn-confirm-order">Confirm Order</button>
                </div>
            </div>
        </div>

        <!-- Right Panel - Display with Selection Options -->
        <div class="w-2/3 h-auto bg-[#EFE5D9] rounded-[35px] flex items-center justify-center p-4 relative">
            <div id="main-cookie-display" class="text-center">
                <div id="cookie-preview">
                    <img src="{{ asset('images/circle.png') }}" alt="Shape Preview" id="shape-preview">
                    <img src="{{ asset('images/circle_basic.png') }}" alt="Color Preview" id="color-preview">
                    <img src="" alt="Topping Preview" id="topping-preview-img">
                </div>
                {{-- <p class="text-[#783F12] mt-4 text-xl" id="cookie-description">Circle - Basic - Shaped Sprinkles</p> --}}

                @php
                    $cookieShapes = [
                        ['img' => 'circle.png', 'name' => 'Circle'],
                        ['img' => 'heart.png', 'name' => 'Heart'],
                    ];

                    $circleCookies = [
                        // Colors/Variations for Circle shape
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
                        // Colors/Variations for Heart shape
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

                    $toppings = [
                        ['img' => 'shaped_sprinkles.png', 'name' => 'Shaped Sprinkles'],
                        ['img' => 'rainbow_sprinkles.png', 'name' => 'Rainbow Sprinkles'],
                        ['img' => 'choco_sprinkles.png', 'name' => 'Choco Sprinkles'],
                        ['img' => 'mix_sprinkles.png', 'name' => 'Mix Sprinkles'],
                        ['img' => 'marshmallow.png', 'name' => 'Marshmallow'],
                        ['img' => 'chocolate.png', 'name' => 'Chocolate'],
                        ['img' => 'none.png', 'name' => 'None'], 
                    ];
                @endphp

                <!-- Shape options preview -->
                <div class="options-preview active" id="shape-options-preview">
                    @foreach ($cookieShapes as $index => $cookieShape)
                        <img src="{{ asset('images/' . $cookieShape['img']) }}" alt="{{ $cookieShape['name'] }}"
                            class="option-thumbnail {{ $index === 0 ? 'selected' : '' }}"
                            data-shape="{{ $cookieShape['name'] }}"
                            data-img="{{ asset('images/' . $cookieShape['img']) }}">
                    @endforeach
                </div>

                <!-- Color options preview -->
                <div class="options-preview" id="color-options-preview">
                    {{-- Content will be dynamically generated by JavaScript --}}
                </div>

                <!-- Topping options preview -->
                <div class="options-preview" id="topping-options-preview">
                    {{-- Content will be dynamically generated by JavaScript --}}
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Convert PHP arrays to JavaScript
                const cookieOptions = {
                    shapes: @json($cookieShapes),
                    circle: @json($circleCookies),
                    heart: @json($heartCookies),
                    toppings: @json($toppings) 
                };

                // DOM Elements
                const stepShape = document.getElementById('step-shape');
                const stepColor = document.getElementById('step-color');
                const stepTopping = document.getElementById('step-topping'); // New step

                const btnNextShape = document.getElementById('btn-next-shape');
                const btnNextColor = document.getElementById('btn-next-color');
                const btnConfirmOrder = document.getElementById('btn-confirm-order'); // New confirm button

                const btnBackColor = document.getElementById('btn-back-color');
                const btnBackTopping = document.getElementById('btn-back-topping'); // New back button

                const shapePreviewImg = document.getElementById('shape-preview'); // Renamed for clarity
                const colorPreviewImg = document.getElementById('color-preview'); // Renamed for clarity
                const toppingPreviewImg = document.getElementById('topping-preview-img'); // New topping preview image

                // const cookieDescription = document.getElementById('cookie-description');

                const shapeOptionsPreview = document.getElementById('shape-options-preview');
                const colorOptionsPreview = document.getElementById('color-options-preview');
                const toppingOptionsPreview = document.getElementById('topping-options-preview'); // New topping options

                // Current selection state
                let currentShape = cookieOptions.shapes[0].name;
                let currentShapeImg = "{{ asset('images/') }}/" + cookieOptions.shapes[0].img;

                let currentColor = cookieOptions.circle[0].name; // Default to first circle color
                let currentColorImg = "{{ asset('images/') }}/" + cookieOptions.circle[0].img;

                let currentTopping = cookieOptions.toppings[0].name; // Default to first topping
                let currentToppingImg = "{{ asset('images/') }}/" + cookieOptions.toppings[0].img;

                // --- Helper function to update main cookie description ---
                // function updateMainDescription() {
                //     cookieDescription.textContent = `${currentShape} - ${currentColor} - ${currentTopping}`;
                // }

                // --- Initialize Previews and Description ---
                shapePreviewImg.src = currentShapeImg;
                colorPreviewImg.src = currentColorImg;
                toppingPreviewImg.src = currentToppingImg; // Set initial topping image
                // updateMainDescription();
                updateColorOptions(currentShape); // Initialize color options for default shape
                updateToppingOptions(); // Initialize topping options


                // --- Shape Selection ---
                document.querySelectorAll('#shape-options-preview .option-thumbnail').forEach(option => {
                    option.addEventListener('click', function() {
                        document.querySelectorAll('#shape-options-preview .option-thumbnail').forEach(
                            thumb => thumb.classList.remove('selected'));
                        this.classList.add('selected');
                        currentShape = this.dataset.shape;
                        currentShapeImg = this.dataset.img;
                        shapePreviewImg.src = currentShapeImg;
                        // When shape changes, colors available might change, and default color should reset.
                        updateColorOptions(
                            currentShape); // This will also update currentColor and its image
                        // updateMainDescription();
                    });
                });

                btnNextShape.addEventListener('click', function() {
                    stepShape.classList.remove('active');
                    stepColor.classList.add('active');
                    shapeOptionsPreview.classList.remove('active');
                    colorOptionsPreview.classList.add('active');
                    // updateColorOptions(currentShape); // Already called on shape click/init
                });

                // --- Color Selection ---
                btnBackColor.addEventListener('click', function() {
                    stepColor.classList.remove('active');
                    stepShape.classList.add('active');
                    colorOptionsPreview.classList.remove('active');
                    shapeOptionsPreview.classList.add('active');
                });

                btnNextColor.addEventListener('click', function() {
                    stepColor.classList.remove('active');
                    stepTopping.classList.add('active');
                    colorOptionsPreview.classList.remove('active');
                    toppingOptionsPreview.classList.add('active');
                    // updateToppingOptions(); // Already called on init
                });

                function updateColorOptions(shape) {
                    colorOptionsPreview.innerHTML = ''; // Clear existing options
                    const colors = shape.toLowerCase() === 'circle' ? cookieOptions.circle : cookieOptions.heart;

                    if (colors.length === 0) return; // Should not happen with current data

                    colors.forEach((color, index) => {
                        const colorThumb = document.createElement('img');
                        colorThumb.src = `{{ asset('images/') }}/${color.img}`;
                        colorThumb.alt = color.name;
                        colorThumb.className = `option-thumbnail`;
                        if (index === 0) { // Select the first one by default
                            colorThumb.classList.add('selected');
                            currentColor = color.name; // Update current color
                            currentColorImg = `{{ asset('images/') }}/${color.img}`;
                            colorPreviewImg.src = currentColorImg;
                        }
                        colorThumb.dataset.color = color.name;
                        colorThumb.dataset.img = `{{ asset('images/') }}/${color.img}`;

                        colorThumb.addEventListener('click', function() {
                            document.querySelectorAll('#color-options-preview .option-thumbnail')
                                .forEach(thumb => thumb.classList.remove('selected'));
                            this.classList.add('selected');
                            currentColor = this.dataset.color;
                            currentColorImg = this.dataset.img;
                            colorPreviewImg.src = currentColorImg;
                            // updateMainDescription();
                        });
                        colorOptionsPreview.appendChild(colorThumb);
                    });
                    // Ensure the first color is active in preview and description
                    if (colors.length > 0) {
                        currentColor = colors[0].name;
                        currentColorImg = `{{ asset('images/') }}/${colors[0].img}`;
                        colorPreviewImg.src = currentColorImg;
                        document.querySelector('#color-options-preview .option-thumbnail').classList.add('selected');
                        // updateMainDescription();
                    }
                }


                // --- Topping Selection ---
                btnBackTopping.addEventListener('click', function() {
                    stepTopping.classList.remove('active');
                    stepColor.classList.add('active');
                    toppingOptionsPreview.classList.remove('active');
                    colorOptionsPreview.classList.add('active');
                });

                function updateToppingOptions() {
                    toppingOptionsPreview.innerHTML = ''; // Clear existing options
                    const toppings = cookieOptions.toppings;

                    if (toppings.length === 0) return;

                    toppings.forEach((topping, index) => {
                        const toppingThumb = document.createElement('img');
                        toppingThumb.src = `{{ asset('images/') }}/${topping.img}`;
                        toppingThumb.alt = topping.name;
                        toppingThumb.className = 'option-thumbnail';
                        if (index === 0) { // Select the first one by default
                            toppingThumb.classList.add('selected');
                            currentTopping = topping.name; // Update current topping
                            currentToppingImg = `{{ asset('images/') }}/${topping.img}`;
                            toppingPreviewImg.src = currentToppingImg;
                        }
                        toppingThumb.dataset.topping = topping.name;
                        toppingThumb.dataset.img = `{{ asset('images/') }}/${topping.img}`;

                        toppingThumb.addEventListener('click', function() {
                            document.querySelectorAll('#topping-options-preview .option-thumbnail')
                                .forEach(thumb => thumb.classList.remove('selected'));
                            this.classList.add('selected');
                            currentTopping = this.dataset.topping;
                            currentToppingImg = this.dataset.img;
                            toppingPreviewImg.src =
                                currentToppingImg; // Update main topping preview image
                            // updateMainDescription();
                        });
                        toppingOptionsPreview.appendChild(toppingThumb);
                    });
                    // Ensure the first topping is active in preview and description
                    if (toppings.length > 0) {
                        currentTopping = toppings[0].name;
                        currentToppingImg = `{{ asset('images/') }}/${toppings[0].img}`;
                        toppingPreviewImg.src = currentToppingImg;
                        if (document.querySelector('#topping-options-preview .option-thumbnail')) {
                            document.querySelector('#topping-options-preview .option-thumbnail').classList.add(
                                'selected');
                        }
                        // updateMainDescription();
                    }
                }

                // --- Form Submission ---
                btnConfirmOrder.addEventListener('click', function() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('order.confirm') }}"; // Ensure this route exists

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
                    const shapeImgInput = document.createElement('input');
                    shapeImgInput.type = 'hidden';
                    shapeImgInput.name = 'shape_img';
                    shapeImgInput.value = currentShapeImg;
                    form.appendChild(shapeImgInput);

                    // Add color data
                    const colorInput = document.createElement('input');
                    colorInput.type = 'hidden';
                    colorInput.name = 'color';
                    colorInput.value = currentColor;
                    form.appendChild(colorInput);
                    const colorImgInput = document.createElement('input');
                    colorImgInput.type = 'hidden';
                    colorImgInput.name = 'color_img';
                    colorImgInput.value = currentColorImg;
                    form.appendChild(colorImgInput);

                    // Add topping data
                    const toppingInput = document.createElement('input');
                    toppingInput.type = 'hidden';
                    toppingInput.name = 'topping';
                    toppingInput.value = currentTopping;
                    form.appendChild(toppingInput);
                    const toppingImgInput = document.createElement('input');
                    toppingImgInput.type = 'hidden';
                    toppingImgInput.name = 'topping_img';
                    toppingImgInput.value = currentToppingImg;
                    form.appendChild(toppingImgInput);

                    document.body.appendChild(form);
                    form.submit();
                });
            });
        </script>
</body>

</html>
