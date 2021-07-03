@extends('admin.admin-main')

@section('title')
{{ $title ?? 'Default' }}
@stop

@section('content')
<div class="container my-5">

    {{-- title --}}
	<div class="row mb-5">
		<div class="col12">
			<h3>Edit Frontpage</h3>
		</div>
	</div>

    {{-- post form --}}
	<form
        action="{{ route('frontpage-update', ['id' => $page->id]) }}" method="POST"
        class="post-form" enctype="multipart/form-data">
		@csrf
        @method('PATCH')
		<div class="row create-post-form">

            {{-- input field --}}
			<div class="col-lg-9 col-12">
                <input type="hidden" name="post_id" value="{{ $page->id }}">

                {{-- title --}}
				<input
                    type="text" name="post_title" class="form-control mb-1 @error('post_title') is-invalid @enderror"
                    placeholder="Post title" value="{{ old('post_title') ?? $page->post_title }}"
                    readonly>
				@error('post_title') <div class="invalid-feedback mb-3">{{ $message }}</div> @enderror

                {{-- custom field --}}
                <div class="my-4" id="fpCustomField">
                    {{-- tab --}}
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Sliders</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Wording</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Highlight</button>
                        </li>
                    </ul>

                    {{-- content field --}}
                    <div class="tab-content" id="myTabContent">
                        <div
                            class="tab-pane fade show active table-responsive" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            <table class="table" id="sliderTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th style="width: 35%">Title</th>
                                        <th style="width: 40%">Wording</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>

                                {{-- row 1 --}}
                                @isset($custom_fields)
                                    @foreach ($custom_fields as $field)
                                        @php $i = $j = 0; @endphp
                                        @foreach ($field as $key => $value)
                                            @php $i++; @endphp
                                            <tr class="mb-3 fieldRow" id="fieldRow_{{ $j }}" data-field-number="{{ $j }}">
                                                <td>{{ $i }}</td>
                                                <td>
                                                    <img src="{{ imgDefault() }}" alt="" class="w-75 mb-4">
                                                    <input
                                                        class="form-control" type="file" name="slider[field_frontpage][{{ $key }}][image]"
                                                    >
                                                </td>
                                                <td>
                                                    <input
                                                        type="text" class="form-control"
                                                        name="slider[field_frontpage][{{ $key }}][title]"
                                                        value="{{ $value->title }}"
                                                        >
                                                </td>
                                                <td>
                                                    <textarea
                                                        class="form-control" cols="8" rows="5"
                                                        name="slider[field_frontpage][{{ $key }}][wording]">{{ $value->wording }}</textarea>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="text-decoration-none deleteRow">&#10006;</a>
                                                </td>
                                            </tr>
                                            @php $j++; @endphp
                                        @endforeach
                                    @endforeach
                                @endisset

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: right">
                                            <button type="button" class="btn btn-primary" id="addRowBtn">Add Row</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div
                            class="tab-pane fade" id="profile" role="tabpanel"
                            aria-labelledby="profile-tab">Wording</div>
                        <div
                            class="tab-pane fade" id="contact" role="tabpanel"
                            aria-labelledby="contact-tab">Highlight</div>
                    </div>

                </div>

			</div>

            {{-- right bar --}}
			<div class="col-lg-3 col-12">
                <div class="accordion mb-4" id="pagestatusAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="pagestatusDialog">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsepagestatus" aria-expanded="true" aria-controls="collapsepagestatus">
                                Post Status
                            </button>
                        </h2>
                        <div id="collapsepagestatus" class="accordion-collapse collapse show" aria-labelledby="pagestatusDialog" data-bs-parent="#pagestatusAccordion">
                            <div class="accordion-body">
                                <div class="card-body d-flex justify-content-between">
					                <input type="submit" name="post_status" class="btn btn-primary text-capitalize" value="Update">
					            </div>
                            </div>
                        </div>
                    </div>
                </div>

			</div>

		</div>
	</form>
</div>
@endsection


@section('script')
<script>
$(function(){

    let remove_img_preview = $('.post-edit a#removeImgPreview');
    let img_preview = $('#imgPreview');
    remove_img_preview.on('click', function(){

        function imgSrcCheck(){
            if( img_preview.attr('src').length != '' ){
                remove_img_preview.css('display', 'block');
            }else{
                remove_img_preview.css('display', 'none');
            }
        }

        if(confirm('Are you sure ?') == true){
            img_preview.attr('src', '');
            img_preview.attr('alt', '');
            $('[name=post_img_feature]').val('');
            imgSrcCheck();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: "{{ route('pages-edit-remove-image') }}",
                data: {
                    post_id: $('[name=post_id]').val()
                },
                success: function (res) {
                    console.log(res)
                }
            });
        }

    });

    /*
    * custom field
    */
    var addRowBtn = $('#addRowBtn');
    addRowBtn.on('click', function(){
        var fieldRow = $('tr.fieldRow'); // get last row value

        fieldRowCount = fieldRow.length;
        numbering = fieldRowCount+1;
        $(`
            <tr class="mb-3 fieldRow" id="fieldRow_`+ fieldRowCount +`" data-field-number="`+fieldRowCount+`">
                <td>`+ numbering +`</td>
                <td>
                    <img src="{{ imgDefault() }}" alt="" class="w-75 mb-4">
                    <input class="form-control" type="file" name="slider[field_frontpage][row-`+fieldRowCount+`][image]">
                </td>
                <td>
                    <input type="text" class="form-control" name="slider[field_frontpage][row-`+fieldRowCount+`][title]">
                </td>
                <td>
                    <textarea class="form-control" name="slider[field_frontpage][row-`+fieldRowCount+`][wording]" cols="8" rows="5"></textarea>
                </td>
                <td>
                    <a href="javascript:void(0)" class="text-decoration-none deleteRow">&#10006;</a>
                </td>
            </tr>
        `).appendTo('#sliderTable tbody');
        deleteRowHandle()
    });

    function deleteRowHandle(){
        var deleteRow = $('.deleteRow');
        deleteRow.on('click', function(){
            $this = $(this);
            $this.parents('tr').remove();
        })
    }deleteRowHandle()

})


</script>
@stop
