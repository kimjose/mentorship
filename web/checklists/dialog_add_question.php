<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Umb\Mentorship\Models\Question;
use Umb\Mentorship\Models\Frequency;

$sectionId = $_GET['section_id'];
$id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $question = Question::findOrFail($id);
}
$frequencies = Frequency::all();
?>
<div class="container-fluid">
    <form action="" id="manage-question">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-6 border-right">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="section_id" value="<?php echo $sectionId ?>">
                    <div class="form-group">
                        <label for="" class="control-label">Question</label>
                        <textarea name="question" id="" cols="30" rows="4" class="form-control"><?php echo isset($id) ? $question->question : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Frequency</label>
                        <select name="frequency_id" id="selectFrequency" required class="form-control">
                            <option hidden value="" <?php echo !isset($id) ? 'required' : '' ?>> Select frequency </option>
                            <?php foreach ($frequencies as $frequency) : ?>
                                <option value="<?php echo $frequency->id ?>" <?php echo (isset($id) &&  '') ? 'selected' : ''  ?> > <?php echo $frequency->name ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Question Answer Type</label>
                        <select name="type" id="type" class="custom-select custom-select-sm">
                            <!-- TODO: Add conditions -->
                            <option value="" hidden selected="">Please Select here</option>
                            <option value="radio_opt">Single Answer/Radio Button</option>
                            <option value="check_opt">Multiple Answer/Check Boxes</option>
                            <option value="textfield_s">Text Field/ Text Area</option>
                        </select>
                    </div>

                </div>
                <div class="col-sm-6">
                    <b>Preview</b>
                    <div class="preview">
                        <?php if (!isset($id)) : ?>
                            <center><b>Select Question Answer type first.</b></center>
                        <?php else : ?>
                            <div class="callout callout-info">
                                <?php if ($question->type != 'textfield_s') :
                                    $opt = $question->type == 'radio_opt' ? 'radio' : 'checkbox';
                                ?>
                                    <table width="100%" class="table">
                                        <colgroup>
                                            <col width="10%">
                                            <col width="80%">
                                            <col width="10%">
                                        </colgroup>
                                        <thead>
                                            <tr class="">
                                                <th class="text-center"></th>

                                                <th class="text-center">
                                                    <label for="" class="control-label">Label</label>
                                                </th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            foreach (json_decode($question->frm_option) as $k => $v) :
                                                $i++;
                                            ?>
                                                <tr class="">
                                                    <td class="text-center">
                                                        <div class="icheck-primary d-inline" data-count='<?php echo $i ?>'>
                                                            <input type="<?php echo $opt ?>" id="<?php echo $opt ?>Primary<?php echo $i ?>" name="<?php echo $opt ?>" checked="">
                                                            <label for="<?php echo $opt ?>Primary<?php echo $i ?>">
                                                            </label>
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <input type="text" class="form-control form-control-sm check_inp" name="label[]" value="<?php echo $v ?>">
                                                    </td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <button class="btn btn-sm btn-flat btn-default" type="button" onclick="<?php echo $type ?>($(this))"><i class="fa fa-plus"></i> Add</button>
                                        </div>
                                    </div>
                            </div>
                    </div>

                <?php else : ?>
                    <textarea name="frm_opt" id="" cols="30" rows="10" class="form-control" disabled="" placeholder="Write Something here..."></textarea>
                <?php endif; ?>
            <?php endif; ?>
                </div>
            </div>
        </div>
</div>
</form>
</div>
<div id="check_opt_clone" style="display: none">
    <div class="callout callout-info">
        <table width="100%" class="table">
            <colgroup>
                <col width="10%">
                <col width="80%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr class="">
                    <th class="text-center"></th>

                    <th class="text-center">
                        <label for="" class="control-label">Label</label>
                    </th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="">
                    <td class="text-center">
                        <div class="icheck-primary d-inline" data-count='1'>
                            <input type="checkbox" id="checkboxPrimary1" checked="">
                            <label for="checkboxPrimary1">
                            </label>
                        </div>
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control form-control-sm check_inp" name="label[]">
                    </td>
                    <td class="text-center"></td>
                </tr>
                <tr class="">
                    <td class="text-center">
                        <div class="icheck-primary d-inline" data-count='2'>
                            <input type="checkbox" id="checkboxPrimary2">
                            <label for="checkboxPrimary2">
                            </label>
                        </div>
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control form-control-sm check_inp" name="label[]">
                    </td>
                    <td class="text-center"></td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-flat btn-default" type="button" onclick="new_check($(this))"><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
    </div>
</div>
<div id="radio_opt_clone" style="display: none">
    <div class="callout callout-info">
        <table width="100%" class="table">
            <colgroup>
                <col width="10%">
                <col width="80%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr class="">
                    <th class="text-center"></th>

                    <th class="text-center">
                        <label for="" class="control-label">Label</label>
                    </th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="">
                    <td class="text-center">
                        <div class="icheck-primary d-inline" data-count='1'>
                            <input type="radio" id="radioPrimary1" name="radio" checked="">
                            <label for="radioPrimary1">
                            </label>
                        </div>
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control form-control-sm check_inp" name="label[]">
                    </td>
                    <td class="text-center"></td>
                </tr>
                <tr class="">
                    <td class="text-center">
                        <div class="icheck-primary d-inline" data-count='2'>
                            <input type="radio" id="radioPrimary2" name="radio">
                            <label for="radioPrimary2">
                            </label>
                        </div>
                    </td>

                    <td class="text-center">
                        <input type="text" class="form-control form-control-sm check_inp" name="label[]">
                    </td>
                    <td class="text-center"></td>
                </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-sm-12 text-center">
                <button class="btn btn-sm btn-flat btn-default" type="button" onclick="new_radio($(this))"><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
    </div>
</div>
<div id="textfield_s_clone" style="display: none">
    <div class="callout callout-info">
        <textarea name="frm_opt" id="" cols="30" rows="10" class="form-control" disabled="" placeholder="Write Something here..."></textarea>
    </div>
</div>
<script>
    function new_check(_this) {
        var tbody = _this.closest('.row').siblings('table').find('tbody')
        var count = tbody.find('tr').last().find('.icheck-primary').attr('data-count')
        count++;
        console.log(count)
        var opt = '';
        opt += '<td class="text-center pt-1"><div class="icheck-primary d-inline" data-count = "' + count + '"><input type="checkbox" id="checkboxPrimary' + count + '"><label for="checkboxPrimary' + count + '"> </label></div></td>';
        opt += '<td class="text-center"><input type="text" class="form-control form-control-sm check_inp" name="label[]"></td>';
        opt += '<td class="text-center"><a href="javascript:void(0)" onclick="$(this).closest(\'tr\').remove()"><span class="fa fa-times" ></span></a></td>';
        var tr = $('<tr></tr>')
        tr.append(opt)
        tbody.append(tr)
    }

    function new_radio(_this) {
        var tbody = _this.closest('.row').siblings('table').find('tbody')
        var count = tbody.find('tr').last().find('.icheck-primary').attr('data-count')
        count++;
        console.log(count)
        var opt = '';
        opt += '<td class="text-center pt-1"><div class="icheck-primary d-inline" data-count = "' + count + '"><input type="radio" id="radioPrimary' + count + '" name="radio"><label for="radioPrimary' + count + '"> </label></div></td>';
        opt += '<td class="text-center"><input type="text" class="form-control form-control-sm check_inp" name="label[]"></td>';
        opt += '<td class="text-center"><a href="javascript:void(0)" onclick="$(this).closest(\'tr\').remove()"><span class="fa fa-times" ></span></a></td>';
        var tr = $('<tr></tr>')
        tr.append(opt)
        tbody.append(tr)
    }

    function check_opt() {
        var check_opt_clone = $('#check_opt_clone').clone()
        $('.preview').html(check_opt_clone.html())
    }

    function radio_opt() {
        var radio_opt_clone = $('#radio_opt_clone').clone()
        $('.preview').html(radio_opt_clone.html())
    }

    function textfield_s() {
        var textfield_s_clone = $('#textfield_s_clone').clone()
        $('.preview').html(textfield_s_clone.html())
    }
    $('[name="type"]').change(function() {
        window[$(this).val()]()
    })
    $(function() {
        $('#manage-question').submit(function(e) {
            e.preventDefault()
            let formData = new FormData($(this)[0])
            let question = {};
            for (let [key, value] of formData.entries()) {
                question[key] = value
            }
            start_load()


            $.ajax({
                url: '../api/question',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    if (resp.code == 200) {
                        alert_toast('Data successfully saved.', "success");
                        setTimeout(function() {
                            location.reload()
                        }, 1500)
                    } else {
                        toastr.error(resp.message)
                    }
                },
                error: function(request, status, error) {
                    alert(request.responseText);
                }
            })
        })

    })
</script>