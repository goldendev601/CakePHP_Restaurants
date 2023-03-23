<div class="content-wrapper">
    <section class="content-header">
        <h1>
           Manage Newsletter
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>            
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box my-box">
                    <div class="box-header">

                    </div>
                    <?php
                    echo $this->Form->create('Newsletter', ['class'=>'form-horizontal', 'url' =>'/foodadmin/Newsletters/sendselectcustomer']); ?>
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="" class="btn-group pull-right"> <?php
                                        if (!empty($cusList)) {
                                            echo $this->Html->link(
                                                'Send mail to all users',
                                                [
                                                    'action' => 'sendall',
                                                    'escape' => false
                                                ],
                                                [
                                                    'class'=>'btn blue'
                                                ]
                                            );
                                            echo $this->Form->button(__('Send mail to Selected users'),
                                                ['class'=>'btn green',
                                                    'id' => 'send',
                                                    'type' => 'submit',
                                                    'style' => 'display:none']);
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body" id="ajaxReplace">
                            <table class="table table-striped table-bordered table-hover checktable" id="newslettertable">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox no-sort"><input id="test1" type="checkbox" class="group-checkable test1" data-set="#sample_1 .checkboxes"/></th>
                                        <th>User Name</th>
                                        <th>Customer Email</th>
                                        <th>Customer Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($cusList)) {
                                        foreach($cusList as $key => $value) {
                                            //echo "<pre>"; print_r($value); die(); ?>
                                            <tr>
                                                <td><?php
                                            echo $this->Form->checkbox($value['id'],[
                                                    'class' => 'checkboxes test',
                                                    'name' => 'email[]',
                                                    'multiple' => 'checkbox',
                                                    'label' => false,
                                                    'hiddenField' => false,
                                                    'id' => 'test',
                                                    'value' => $value['username']
                                                    ]); ?></td>
                                                <td><?php echo $value['first_name'] ;?></td>
                                                <td><?php echo $value['username'] ;?></td>
                                                <td><?php echo $value['phone_number'] ;?></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
$(document).ready(function (){   
   var table = $('#example').DataTable({
      'ajax': 'https://api.myjson.com/bins/1us28',  
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
             return '<input type="checkbox" name="id[]" value="' 
                + $('<div/>').text(data).html() + '">';
         }
      }],
      'order': [1, 'asc']
   });

$(document).ready(function() {
    $('#newslettertable').DataTable({

        columnDefs: [
            {
                "bSortable" : false,
                "aTargets" : [ "no-sort" ]
            }
        ],
        drawCallback: function(){
            $('.paginate_button').on('click', function(){
                $('.green').hide();
                $(".checktable th input[type='checkbox']").change(function(){
                    if($(this).prop("checked") == true){
                        $(".checktable td input[type='checkbox']").prop("checked",true);
                        $(".checktable td input[type='checkbox']").parent().addClass("checked");
                        $("#send").show();
                    }
                    else{
                        $(".checktable td input[type='checkbox']").prop("checked",false);
                        $(".checktable td input[type='checkbox']").parent().removeClass("checked");
                        $("#send").hide();
                    }
                });

                $(".checktable td input[type='checkbox']").change(function(){
                    var length = $(".checktable tbody tr td input[type='checkbox']").length;
                    var checklength = $(".checktable tbody tr td input[type='checkbox']:checked").length;

                    if(length == checklength){
                        $(".checktable th input[type='checkbox']").prop("checked",true);
                        $(".checktable th input[type='checkbox']").parent().addClass("checked");
                        $("#send").show();
                    } else if(checklength > 0){
                        $(".checktable th input[type='checkbox']").prop("checked",false);
                        $(".checktable th input[type='checkbox']").parent().removeClass("checked");
                        $("#send").show();
                    } else{
                        $(".checktable th input[type='checkbox']").prop("checked",false);
                        $(".checktable th input[type='checkbox']").parent().removeClass("checked");
                        $("#send").hide();
                    }
                });
                $(".checktable td input[type='checkbox']").change(function(){
                    if($(".checktable td input[type='checkbox']").is(':checked',true)) {
                        $(".green").show();
                    }
                    else{
                        $(".green").hide();
                    }
                });
            });
        }
    });
} );

$(document).ready(function() {
    $(".checktable th input[type='checkbox']").change(function(){
        if($(this).prop("checked") == true){
            $(".checktable td input[type='checkbox']").prop("checked",true);
            $(".checktable td input[type='checkbox']").parent().addClass("checked");
            $("#send").show();
        }
        else{
            $(".checktable td input[type='checkbox']").prop("checked",false);
            $(".checktable td input[type='checkbox']").parent().removeClass("checked");
            $("#send").hide();
        }
    });

    $(".checktable td input[type='checkbox']").change(function(){
        var length = $(".checktable tbody tr td input[type='checkbox']").length;
        var checklength = $(".checktable tbody tr td input[type='checkbox']:checked").length;

        if(length == checklength){
            $(".checktable th input[type='checkbox']").prop("checked",true);
            $(".checktable th input[type='checkbox']").parent().addClass("checked");
            $("#send").show();
        } else if(checklength > 0){
            $(".checktable th input[type='checkbox']").prop("checked",false);
            $(".checktable th input[type='checkbox']").parent().removeClass("checked");
            $("#send").show();
        } else{
            $(".checktable th input[type='checkbox']").prop("checked",false);
            $(".checktable th input[type='checkbox']").parent().removeClass("checked");
            $("#send").hide();
        }
    });
});



   // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });

   // Handle click on checkbox to set state of "Select all" control
   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
    
   $('#frm-example').on('submit', function(e){
      var form = this;

      // Iterate over all checkboxes in the table
      table.$('input[type="checkbox"]').each(function(){
         // If checkbox doesn't exist in DOM
         if(!$.contains(document, this)){
            // If checkbox is checked
            if(this.checked){
               // Create a hidden element 
               $(form).append(
                  $('<input>')
                     .attr('type', 'hidden')
                     .attr('name', this.name)
                     .val(this.value)
               );
            }
         } 
      });

      // FOR TESTING ONLY
      
      // Output form data to a console
      $('#example-console').text($(form).serialize()); 
      console.log("Form submission", $(form).serialize()); 
       
      // Prevent actual form submission
      e.preventDefault();
   });
});
/*$(document).ready(function () {
    var checkbox = 1;
    $(".test1").on("click", function () {
        if (checkbox == 0) {
            checkbox = 1;
            $("#send").hide();
        } else {
            checkbox = 0;
            $("#send").show();
        }
    });
});

$('#sample_12').dataTable( {
  columnDefs: [
    {
      "bSortable" : false,
      "aTargets" : [ "no-sort" ]
    }
  ],
   drawCallback: function(){
        $('.paginate_button').on('click', function(){
            $('.green').hide();
            $(".checktable th input[type='checkbox']").change(function(){
                if($(this).prop("checked") == true){
                    $(".checktable td input[type='checkbox']").prop("checked",true);
                    $(".checktable td input[type='checkbox']").parent().addClass("checked");
                    $("#send").show();
                }
                else{
                    $(".checktable td input[type='checkbox']").prop("checked",false);
                    $(".checktable td input[type='checkbox']").parent().removeClass("checked");
                    $("#send").hide();
                }
            });

            $(".checktable td input[type='checkbox']").change(function(){
                var length = $(".checktable tbody tr td input[type='checkbox']").length;
                var checklength = $(".checktable tbody tr td input[type='checkbox']:checked").length;

                if(length == checklength){
                    $(".checktable th input[type='checkbox']").prop("checked",true);
                    $(".checktable th input[type='checkbox']").parent().addClass("checked");
                    $("#send").show();
                } else if(checklength > 0){
                    $(".checktable th input[type='checkbox']").prop("checked",false);
                    $(".checktable th input[type='checkbox']").parent().removeClass("checked");
                    $("#send").show();
                } else{
                    $(".checktable th input[type='checkbox']").prop("checked",false);
                    $(".checktable th input[type='checkbox']").parent().removeClass("checked");
                    $("#send").hide();
                }
            });
            $(".checktable td input[type='checkbox']").change(function(){
                if($(".checktable td input[type='checkbox']").is(':checked',true)) {
                    $(".green").show();
                }
                else{
                    $(".green").hide();
                }
            });
        });
    }
});*/
</script>