@extends('layouts.layoutMaster')
@section('title',env('WEB_NAME').' | Question Edit')
@section('page-style')
    <style>
        .input_image_div {
            max-width: 41px;
            margin-right: 12px;
        }
        .input_image {
            max-width: -webkit-fill-available;
        }
        ::file-selector-button {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Question Bank</h1>
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible mx-3">
                <div class="d-flex gap-2">
                    <h5><i class="icon fas fa-check"></i></h5>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert  alert-danger alert-dismissible mx-3">
                <div class="d-flex gap-2">
                    <h5><i class="icon fas fa-ban"></i></h5>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
            </div>
        @endif

        <section class="content m-2">
            <div class="card card-primary">
                <form id="question-edit" action="{{ route('question.update', $data->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="std">Year</label>
                            <select name="std" id="std" class="form-control" required tabindex="1">
                                <option value="">Select Year</option>
                                <option value="12" {{ ($data->std == 12) ? 'selected' : '' }}>12<sup>th</sup></option>
                                <option value="11" {{ ($data->std == 11) ? 'selected' : '' }}>11<sup>th</sup></option>
                                <option value="10" {{ ($data->std == 10) ? 'selected' : '' }}>10<sup>th</sup></option>
                                <option value="9" {{ ($data->std == 9) ? 'selected' : '' }}>9<sup>th</sup></option>
                                <option value="8" {{ ($data->std == 8) ? 'selected' : '' }}>8<sup>th</sup></option>
                                <option value="7" {{ ($data->std == 7) ? 'selected' : '' }}>7<sup>th</sup></option>
                                <option value="6" {{ ($data->std == 6) ? 'selected' : '' }}>6<sup>th</sup></option>
                                <option value="5" {{ ($data->std == 5) ? 'selected' : '' }}>5<sup>th</sup></option>
                                <option value="4" {{ ($data->std == 4) ? 'selected' : '' }}>4<sup>th</sup></option>
                                <option value="3" {{ ($data->std == 3) ? 'selected' : '' }}>3<sup>rd</sup></option>
                                <option value="2" {{ ($data->std == 2) ? 'selected' : '' }}>2<sup>nd</sup></option>
                                <option value="1" {{ ($data->std == 1) ? 'selected' : '' }}>1<sup>st</sup></option>
                            </select>
                            @error('std')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="topics">Topics</label>&nbsp; <input type="checkbox" id="topic_select_box"
                                                                            tabindex="2"> Select All
                            <select name="topics[]" id="topics" class="form-control select2" multiple required
                                    tabindex="3">
                                <option disabled value="">Select Topics</option>
                                @foreach($topics as $topic)
                                    <option
                                        {{(in_array($topic->id, json_decode($data->topic_id, true) ?? [])) ? 'selected' : ''}} value="{{$topic->id}}">{{$topic->title}}</option>
                                @endforeach
                            </select>
                            @error('topics')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sub_topics">Sub Topics</label>&nbsp; <input type="checkbox"
                                                                                    id="sub_topic_select_box"
                                                                                    tabindex="4"> Select All
                            <select name="sub_topics[]" id="sub_topics" class="form-control select3" multiple required
                                    tabindex="5">
                                <option disabled value="">Select Sub Topics</option>
                            </select>
                            @error('sub_topics')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="difficulty">Difficulty</label>
                            <select name="difficulty" class="form-control" required tabindex="6">
                                <option value="">Select Difficulty</option>
                                <option value="foundation" {{ $data->difficulty == 'foundation' ? 'selected' : '' }}>
                                    Foundation
                                </option>
                                <option
                                    value="intermediate" {{ $data->difficulty == 'intermediate' ? 'selected' : '' }}>
                                    Intermediate
                                </option>
                                <option value="challenging" {{ $data->difficulty == 'challenging' ? 'selected' : '' }}>
                                    Challenging
                                </option>
                            </select>
                            @error('difficulty')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" name="code" id="code" class="form-control" value="{{$data->code}}"
                                   placeholder="Enter Code" required tabindex="7">
                            @error('code')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image">Question Images</label>
                            <div id="image-rows">
                                <div class="input-group">
                                    <input type="file" class="form-control" name="questionimage[]" id="question" tabindex="8">
                                    <button type="button" class="btn btn-primary add-question-image-row">Add Question Image</button>
                                </div>
                                @if(!empty($images))
                                    @foreach($images as $image)
                                        @if(!empty($image->type) && $image->type == 'question')
                                            <div class="input-group mt-3">
                                                <div class="input_image_div">
                                                    <img src="{{ asset('storage/images/' . $image->image_name) }}" alt="image" class="input_image">
                                                </div>
                                                <input type="text" class="form-control" name="existing_question_images[]" value="{{ $image->image_name }}" readonly>
                                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                                <button type="button" class="btn btn-danger remove-image-row" data-image-id="{{ $image->id }}">Remove</button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="image-preview mt-3" id="question-image"></div>
                            </div>
                            @error('question_images')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input type="hidden" name="remove_question_images" id="remove_question_images" value="">
                        </div>

                        <!-- Solution Images -->
                        <div class="form-group">
                            <label for="solutionimage">Solution</label>
                            <div id="solution-image-rows">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="solutionimage[]" tabindex="9" id="solution">
                                    <button type="button" class="btn btn-primary add-solution-image-row">Add Solution Image</button>
                                </div>
                                @if(!empty($images))
                                    @foreach($images as $image)
                                        @if(!empty($image->type) && $image->type == 'solution')
                                            <div class="input-group mb-3">
                                                <div class="input_image_div">
                                                    <img src="{{ asset('storage/images/' . $image->image_name) }}" alt="image" class="input_image">
                                                </div>
                                                <input type="text" class="form-control" value="{{ $image->image_name }}" readonly>
                                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                                <button type="button" class="btn btn-danger remove-image-row" data-image-id="{{ $image->id }}">Remove</button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div id="solution-image"></div>
                            </div>
                            <input type="hidden" name="remove_solution_images" id="remove_solution_images" value="">
                        </div>

                        <!-- Answer Images -->
                        <div class="form-group">
                            <label for="answerimage">Answer</label>
                            <div id="answer-image-rows">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="answerimage[]" tabindex="10" id="answer">
                                    <button type="button" class="btn btn-primary add-answer-image-row">Add Answer Image</button>
                                </div>
                                @if(!empty($images))
                                    @foreach($images as $image)
                                        @if(!empty($image->type) && $image->type == 'answer')
                                            <div class="input-group mb-3">
                                                <div class="input_image_div">
                                                    <img src="{{ asset('storage/images/' . $image->image_name) }}" alt="image" class="input_image">
                                                </div>
                                                <input type="text" class="form-control" value="{{ $image->image_name }}" readonly>
                                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                                <button type="button" class="btn btn-danger remove-image-row" data-image-id="{{ $image->id }}">Remove</button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="image-preview" id="answer-image"></div>
                            </div>
                            <input type="hidden" name="remove_answer_images" id="remove_answer_images" value="">
                        </div>

                        <div class="form-group">
                            <label for="time">Time (in minutes)</label>
                            <input type="number" name="time" id="time" class="form-control"
                                   placeholder="Enter Time" value="{{$data->time / 60}}" required tabindex="11">
                            @error('time')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection

@section('page-script')
    <script>
        document.querySelectorAll('#question, #answer, #solution').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault();
            });
        });

        $(document).ready(function () {

            $("#topic_select_box").click(function () {
                if ($("#topic_select_box").is(':checked')) {
                    $("#topics > option").each(function () {
                        if (!$(this).is(':disabled')) {
                            $(this).prop("selected", true);
                        }
                    });
                } else {
                    $("#topics > option").prop("selected", false);
                }
                $("#topics").trigger("change");
            });
            $("#sub_topic_select_box").click(function () {
                if ($("#sub_topic_select_box").is(':checked')) {
                    $("#sub_topics > option").each(function () {
                        if (!$(this).is(':disabled')) {
                            $(this).prop("selected", true);
                        }
                    });
                } else {
                    $("#sub_topics > option").prop("selected", false);
                }
                $("#sub_topics").trigger("change");
            });

            $('#std').change(function () {
                let selectedStandard = $(this).val();
                if (selectedStandard) {
                    $.ajax({
                        url: '{{env('AJAX_URL')}}' + 'getTopics',
                        type: 'POST',
                        data: {
                            'std': selectedStandard,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (result) {
                            $('#topics').html(result.data);
                            $topics = $('#topics').val();
                            if ($topics.length == 0) {
                                $('#sub_topics').html('<option value="">Please select a topic first</option>');
                            }
                        },
                        error: function (error) {
                            alert('Something went wrong while fetching topics!');
                        }
                    });
                } else {
                    $('#topics').html('<option value="">Please select a topic first</option>');
                    $('#sub_topics').html('<option value="">Please select a topic first</option>');
                }
            });

            if ($('#topics').val()) {
                $.ajax({
                    url: '{{env('AJAX_URL')}}' + 'getSelectedSubTopicData',
                    type: 'POST',
                    data: {
                        'topic_ids': $('#topics').val(),
                        'selected': '{{$data->subtopic_id}}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (result) {
                        $('#sub_topics').html(result.data)
                        $('.select3').select2()
                    },
                    error: function (error) {
                        alert('Something Went Wrong!');
                    }
                });
            }

            const previews = [
                document.getElementById('question-image'),
                document.getElementById('solution-image'),
                document.getElementById('answer-image')
            ];

            let currentInput = null;

            $(document).on('focus', 'input[type="file"]', function () {
                currentInput = this;
            });

            // Function to handle adding new image input fields dynamically
            function addImageRow(containerId, name, buttonClass) {
                $(document).on('click', buttonClass, function () {
                    var newRow = `
        <div class="input-group mb-3">
            <input type="file" class="form-control" name="${name}[]">
            <button type="button" class="btn btn-danger remove-image-row">Remove</button>
        </div>`;
                    $(containerId).append(newRow);
                });
            }
            addImageRow('#image-rows', 'questionimage', '.add-question-image-row');
            addImageRow('#solution-image-rows', 'solutionimage', '.add-solution-image-row');
            addImageRow('#answer-image-rows', 'answerimage', '.add-answer-image-row');


            // Handle removing an image row and the associated preview
            $(document).on('click', '.remove-image-row', function () {
                var imageId = $(this).data('image-id');
                var filename = $(this).data('filename');
                var filedName = $(this).data('filedname');

                var targetFieldId = $(this).closest('#image-rows').length > 0 ? '#remove_question_images' :
                    $(this).closest('#solution-image-rows').length > 0 ? '#remove_solution_images' : '#remove_answer_images';
                if (imageId) {
                    const currentValue = $(targetFieldId).val() ? $(targetFieldId).val().split(',') : [];
                    if (!currentValue.includes(imageId.toString())) {
                        currentValue.push(imageId);
                        $(targetFieldId).val(currentValue.join(','));
                    }
                }

                // Remove the image from the input file field based on filename
                var inputElement = document.getElementById(filedName); // Get the input file element by id
                if (inputElement && inputElement.files) {
                    var dataTransfer = new DataTransfer(); // Create a new DataTransfer object

                    for (let i = 0; i < inputElement.files.length; i++) {
                        // Keep all files except the one that matches the filename to be removed
                        if (inputElement.files[i].name !== filename) {
                            dataTransfer.items.add(inputElement.files[i]);
                        }
                    }

                    // Update the input file field with the new FileList
                    inputElement.files = dataTransfer.files;
                }

                // Remove the image row from the DOM
                $(this).closest('.input-group').remove();
                // Remove the preview image container
                $(this).closest('.file-preview').remove();
            });

            // Function to update the preview with images
            function updatePreview(previewContainer, files, currentInputId) {
                previewContainer.innerHTML = '';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    // Create a container for each file preview
                    const fileContainer = document.createElement('div');
                    fileContainer.classList.add('file-preview');
                    fileContainer.style.display = 'flex';
                    fileContainer.style.alignItems = 'center';
                    fileContainer.style.marginBottom = '10px';

                    // Create and configure the image element
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '39px';
                    img.style.marginRight = '7px';

                    // Create and configure the input element for the file name
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = file.name;
                    input.readOnly = true;
                    input.style.marginLeft = '10px';
                    input.classList.add('form-control');

                    // Create a remove button for the image preview
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.setAttribute('data-imageId',i);
                    removeButton.setAttribute('data-fileName', file.name);
                    removeButton.setAttribute('data-filedName', currentInputId);
                    removeButton.classList.add('btn', 'btn-danger', 'remove-image-row');
                    removeButton.textContent = 'Remove';

                    // Append image, input, and remove button to the file container
                    fileContainer.appendChild(img);
                    fileContainer.appendChild(input);
                    fileContainer.appendChild(removeButton);

                    // Append the file container to the preview container
                    previewContainer.appendChild(fileContainer);

                    // Add event listener to the remove button
                    removeButton.addEventListener('click', function () {
                        // Remove the file from the DataTransfer object and input.files
                        const updatedFiles = Array.from(currentInput.files).filter((f) => f !== file);
                        const newDataTransfer = new DataTransfer();
                        updatedFiles.forEach(f => newDataTransfer.items.add(f));
                        currentInput.files = newDataTransfer.files;

                        // Remove the file container from the preview
                        fileContainer.remove();
                    });
                }
            }

            // Example usage:
            const previewContainer = document.getElementById('answer-image');
            const inputFile = document.querySelector('input[type="file"]');


            inputFile.addEventListener('change', (event) => {
                const files = event.target.files;
                updatePreview(previewContainer, files,'answer-image');
            });



            // Handle paste event for images
            window.addEventListener('paste', function (event) {
                const items = event.clipboardData.items;
                const dataTransfer = new DataTransfer();
                let imagesPasted = false;

                // Loop through clipboard items to check if any images are pasted
                for (let i = 0; i < items.length; i++) {
                    const item = items[i];

                    if (item.type.startsWith('image/')) {
                        const file = item.getAsFile();
                        if (file) {
                            dataTransfer.items.add(file);
                            imagesPasted = true;
                        }
                    }
                }

                if (imagesPasted) {
                    const existingFiles = Array.from(currentInput.files);
                    existingFiles.forEach(file => dataTransfer.items.add(file));
                    currentInput.files = dataTransfer.files;

                    // Update preview based on the input index
                    const inputIndex = Array.from(document.querySelectorAll('input[type="file"]')).indexOf(currentInput);
                    updatePreview(previews[inputIndex], dataTransfer.files,currentInput.id);
                }
            });

            // Handle form submission to log the uploaded files
            document.getElementById('upload-form').addEventListener('submit', function (event) {
                event.preventDefault();
                let fileInputs = [...document.querySelectorAll('input[type="file"]')];

                fileInputs.forEach((input) => {
                    const files = input.files;
                    if (files.length > 0) {
                        console.log(`Files uploaded:`, Array.from(files).map(file => file.name));
                    } else {
                        console.log(`No files selected in this input.`);
                    }
                });
            });
        });
    </script>
    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/qfriyoi7c3pgz0wo25pnp83z6n3l8n2p56ckw8fyjz9oq2a0/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>
    <script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <link rel="stylesheet" href="{{url('assets/plugins/select2/css/select2.css')}}">
    <script src="{{url('assets/plugins/select2/js/select2.full.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2()
            $('.select3').select2()
            // Handle adding new image input fields for question images

            $('#topics').change(function () {
                const selectedOptions = $(this).val(); // This should be an array
                $.ajax({
                    url: '{{env('AJAX_URL')}}' + 'getSubTopicData',
                    type: 'POST',
                    data: {
                        'topic_ids': selectedOptions, // Send as array
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (result) {
                        $('#sub_topics').html(result.data)
                        $('.select3').select2()
                    },
                    error: function (error) {
                        alert('Something Went Wrong!');
                    }
                });
            });
            $("#question-edit").validate({
                rules: {
                    difficulty: {
                        required: true
                    },
                    question: {
                        required: true,
                        minlength: 10
                    },
                },
                messages: {
                    difficulty: {
                        required: "Please select the difficulty level"
                    },
                    question: {
                        required: "Please enter a question",
                        minlength: "Your question must be at least 10 characters long"
                    },
                },
                errorElement: 'div',
                errorPlacement: function (error, element) {
                    error.addClass('text-danger');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Remove image input rows
            $(document).on('click', '.remove-image-row', function () {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            border: 1px solid #007bff !important;
            color: #ffffff !important;
        }
    </style>
@endsection


