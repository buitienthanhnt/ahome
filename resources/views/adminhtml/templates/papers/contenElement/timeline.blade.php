@isset($item)
    <div data-type="p-timeline">
        <p style="display: none">paper timeline</p>
        <div class="data-content form-group">
            <div class="col-md-12 r">
                <label for="{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}" class="col-sm-2 col-form-label">TimeLine:</label>
                <div class="col-sm-10">
                    <div class="form-group">
                        <select id="{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}" class="form-control" name="{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}" multiple="multiple">
                            {!! $time_line_option !!}
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="align-content: center">
                <div class="form-group row" style="margin-bottom: 0px">
                    <label for="url-alias" class="col-sm-2">Timeline:</label>
                    <div class="cs-form col-sm-8">
                        <input name="{{ App\Models\PaperContentInterface::TYPE_TIMELINE }}" id="timelineInput" autocomplete="false" />
                        <script type="text/javascript">
                            // https://gijgo.com/datetimepicker
                            $("#timelineInput").datetimepicker({
                                datepicker: {
                                    showOtherMonths: true,
                                    calendarWeeks: true,
                                    todayHighlight: true
                                },
                                footer: true,
                                modal: true,
                                header: true,
                                value: '{!! $item->value !!}',
                                format: 'yyyy-mm-dd HH:MM:ss',
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div data-type="p-timeline" style="border-radius: 5px">
        <div>
            <div style="display: flex; justify-content: center; align-items: center">
                {{-- https://www.flaticon.com/search?word=picture --}}
                <img src="{{ asset('/assets/adminhtml/images/timeline.png') }}" alt="" style="width: auto; height: 50px;">
            </div>
            <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">TimeLine</p>
        </div>
        <div class="data-content form-group" style="display: none">
            <div class="col-md-12 r">
                <label for="{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}" class="col-sm-2 col-form-label">TimeLine:</label>
                <div class="col-sm-10">
                    <div class="form-group">
                        <select id="{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}" class="form-control" name="{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}" multiple="multiple">
                            {!! $time_line_option !!}
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="align-content: center">
                <div class="form-group row" style="margin-bottom: 0px">
                    <label for="url-alias" class="col-sm-2">Timeline:</label>
                    <div class="cs-form col-sm-8">
                        <input name="{{ App\Models\PaperContentInterface::TYPE_TIMELINE }}" id="timelineInput" />
                        <script type="text/javascript">
                            // https://gijgo.com/datetimepicker
                            $("#timelineInput").datetimepicker({
                                datepicker: {
                                    showOtherMonths: true,
                                    calendarWeeks: true,
                                    todayHighlight: true
                                },
                                footer: true,
                                modal: true,
                                header: true,
                                value: '',
                                format: 'yyyy-mm-dd HH:MM:ss',
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset

<script type="text/javascript">
    $("#{{ App\Models\PaperContentInterface::TYPE_TIMELINE_DEPEND }}").select2({
        placeholder: 'Select an value',
        maximumSelectionLength: 1
    });
</script>
