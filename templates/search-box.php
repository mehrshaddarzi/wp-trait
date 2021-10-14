<script>
    jQuery(document).ready(function ($) {

        // Add Field To Search Box [@see https://developer.wordpress.org/reference/classes/wp_list_table/search_box/]
        $("input#<?php echo $search_input_id; ?>").attr('autocomplete', 'off');
        $(`<select name="search-type" data-current-value="<?php echo $current_value; ?>">
        <?php
        $search_fields = apply_filters('wp_trait_admin_search_box_fields', $search_fields);
        foreach ($search_fields as $name => $value) {

        // Check Value Type
        $type = 'text';
        if (isset($value['type'])) {
            $type = $value['type'];
        }

        // Selected Data
        $choices = '';
        if (isset($value['choices']) and is_array($value['choices']) and !empty($value['choices'])) {
            $choices = json_encode($value['choices'], JSON_NUMERIC_CHECK);
        }

        // Check Title
        if (is_array($value)) {
            $title = $value['title'];
        } else {
            $title = $value;
        }
        ?>
            <option <?php if(!empty($choices)) { ?> data-selected='<?php echo $choices; ?>' <?php } ?> data-type="<?php echo $type; ?>" value="<?php echo $name; ?>" <?php if (isset($_REQUEST['search-type'])) {
            selected($_REQUEST['search-type'], $name);
        } ?>><?php echo $title; ?></option>
            <?php
        }
        ?></select>`).prependTo($("p.search-box"));

        // Handle Select Search
        $(document).on("change", "select[name=search-type]", function (e) {
            e.preventDefault();
            _wp_list_table_search_box_form();
        });

        // Handle Search Box Form
        function _wp_list_table_search_box_form(current_value = '') {
            let opt_selected = $('select[name=search-type] option:selected');
            let option_type = opt_selected.attr('data-type');
            let default_search_input = `<input type="search" id="<?php echo $search_input_id; ?>" name="s" value="` + current_value + `" autocomplete="off">`;
            let post_search_input = $("#<?php echo $search_input_id; ?>");
            let this_value = '';

            switch (option_type) {
                case "select":
                    let option_choices = JSON.parse(opt_selected.attr("data-selected"));
                    let opt_list = `<select id="<?php echo $search_input_id; ?>" name="s">`;
                    Object.entries(option_choices).forEach(([key, val]) => {
                        let selected = '';
                        if (current_value.length > 0 && key == current_value) {
                            selected = ' selected';
                        }
                        opt_list += `<option value="${key}"${selected}>${val}</option>`;
                    });
                    opt_list += `</select>`;
                    post_search_input.replaceWith(opt_list);
                    break;
                case "text":
                    this_value = $("#<?php echo $search_input_id; ?>").val();
                    $("#<?php echo $search_input_id; ?>").replaceWith(default_search_input);
                    if (this_value !== "") {
                        $("#<?php echo $search_input_id; ?>").val(this_value);
                    }
                    break;
            }

            // Show After Render
            $("#<?php echo $search_input_id; ?>").show();
        }

        // Run in Load Page
        let current_value = $("select[name=search-type]").attr('data-current-value');
        _wp_list_table_search_box_form(current_value);
    });
</script>
<style>
    #<?php echo $search_input_id; ?> {  display: none;  }
    select[name=search-type] { margin-top: <?php echo (is_rtl() ? '-2' : '-4'); ?>px; }
</style>