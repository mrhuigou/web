<?php 
use yii\helpers\Html;
?>

<style>
				.wysihtml5-sandbox{margin-top:0!important;}
			 </style>
			 <div class="simditor mt10" id="toolbar">
			     <div class="simditor-toolbar">
			         <ul>
			             <li>
			                 <a class="toolbar-item toolbar-item-title" href="javascript:;" title="排版"><span></span></a>
			                 <div class="toolbar-menu toolbar-menu-title">
			                     <ul>
			                         <li><a class="menu-item menu-item-normal" href="javascript:;" title="正文" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p"><span>正文</span></a></li>
			                         <li><span class="separator"></span></li>
			                         <li><a class="menu-item menu-item-h1" href="javascript:;" title="标题 1" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2"><span>标题 1</span></a></li>
			                         <li><a class="menu-item menu-item-h2" href="javascript:;" title="标题 2" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3"><span>标题 2</span></a></li>
			                         <li><a class="menu-item menu-item-h3" href="javascript:;" title="标题 3" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h4"><span>标题 3</span></a></li>
			                         <li><a class="menu-item menu-item-h4" href="javascript:;" title="标题 4" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h5"><span>标题 4</span></a></li>
			                         <li><a class="menu-item menu-item-h5" href="javascript:;" title="标题 5" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h6"><span>标题 5</span></a></li>
			                     </ul>
			                 </div>
			             </li>
			             <li><a class="toolbar-item toolbar-item-bold" href="javascript:;" title="粗体" data-wysihtml5-command="bold"><span class="simditor-icon simditor-icon-bold"></span></a></li>
			             <li><a class="toolbar-item toolbar-item-italic" href="javascript:;" title="斜体" data-wysihtml5-command="italic"><span class="simditor-icon simditor-icon-italic"></span></a></li>
			             <!-- <li><a class="toolbar-item toolbar-item-underline" href="javascript:;" title="Underline"><span class="simditor-icon simditor-icon-underline"></span></a></li> -->
			             <!-- <li><a class="toolbar-item toolbar-item-strikethrough" href="javascript:;" title="Strikethrough"><span class="simditor-icon simditor-icon-strikethrough"></span></a></li> -->
			             <li>
			                 <a class="toolbar-item toolbar-item-color" href="javascript:;" title="Text Color" data-wysihtml5-command-group="foreColor"><span class="simditor-icon simditor-icon-tint"></span></a>
			                 <div class="toolbar-menu toolbar-menu-color">
			                     <ul class="color-list">
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="1">
			                             <a href="javascript:;" class="font-color font-color-1"></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="2">
			                             <a href="javascript:;" class="font-color font-color-2"></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="3">
			                             <a href="javascript:;" class="font-color font-color-3"></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="4">
			                             <a href="javascript:;" class="font-color font-color-4"></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="5">
			                             <a href="javascript:;" class="font-color font-color-5" ></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="6">
			                             <a href="javascript:;" class="font-color font-color-6"></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="7">
			                             <a href="javascript:;" class="font-color font-color-7"></a>
			                         </li>
			                         <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="8">
			                             <a href="javascript:;" class="font-color font-color-default"></a>
			                         </li>
			                     </ul>
			                 </div>
			             </li>
			             <li><span class="separator"></span></li>
			             <li><a class="toolbar-item toolbar-item-ol" href="javascript:;" title="有序列表" data-wysihtml5-command="insertOrderedList"><span class="simditor-icon simditor-icon-list-ol"></span></a></li>
			             <li><a class="toolbar-item toolbar-item-ul" href="javascript:;" title="无序列表" data-wysihtml5-command="insertUnorderedList"><span class="simditor-icon simditor-icon-list-ul"></span></a></li>
			             <!-- <li><a class="toolbar-item toolbar-item-blockquote" href="javascript:;" title="Block Quote"><span class="simditor-icon simditor-icon-quote-left"></span></a></li> -->
			             <!-- <li><a class="toolbar-item toolbar-item-code" href="javascript:;" title="Code"><span class="simditor-icon simditor-icon-code"></span></a></li> -->
			             <!-- <li>
			                 <a class="toolbar-item toolbar-item-table" href="javascript:;" title="Table"><span class="simditor-icon simditor-icon-table"></span></a>
			                 <div class="toolbar-menu toolbar-menu-table">
			                
			                     <div class="menu-edit-table" style="display: none;">
			                         <ul>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="deleteRow">
			                                     <span>Delete Row</span>
			                                 </a>
			                             </li>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="insertRowAbove">
			                                     <span>Insert Row Above ( Ctrl + Alt + ↑ )</span>
			                                 </a>
			                             </li>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="insertRowBelow">
			                                     <span>Insert Row Below ( Ctrl + Alt + ↓ )</span>
			                                 </a>
			                             </li>
			                             <li><span class="separator"></span></li>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="deleteCol">
			                                     <span>Delete Column</span>
			                                 </a>
			                             </li>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="insertColLeft">
			                                     <span>Insert Column Left ( Ctrl + Alt + ← )</span>
			                                 </a>
			                             </li>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="insertColRight">
			                                     <span>Insert Column Right ( Ctrl + Alt + → )</span>
			                                 </a>
			                             </li>
			                             <li><span class="separator"></span></li>
			                             <li>
			                                 <a class="menu-item" href="javascript:;" data-param="deleteTable">
			                                     <span>Delete Table</span>
			                                 </a>
			                             </li>
			                         </ul>
			                     </div>
			                 </div>
			             </li> -->
			             <li><span class="separator"></span></li>
			             <!-- <li><a class="toolbar-item toolbar-item-link" href="javascript:;" title="Insert Link"><span class="simditor-icon simditor-icon-link"></span></a></li> -->
			             <!-- <li><a class="toolbar-item toolbar-item-image" href="javascript:;" title="Insert Image"><span class="simditor-icon simditor-icon-picture-o"></span></a></li> -->
			             <!-- <li><a class="toolbar-item toolbar-item-hr" href="javascript:;" title="Horizontal Line"><span class="simditor-icon simditor-icon-minus"></span></a></li> -->
			             <!-- <li><span class="separator"></span></li> -->
			             <!-- <li><a class="toolbar-item toolbar-item-indent" href="javascript:;" title="Indent (Tab)"><span class="simditor-icon simditor-icon-indent"></span></a></li> -->
			             <!-- <li><a class="toolbar-item toolbar-item-outdent" href="javascript:;" title="Outdent (Shift + Tab)"><span class="simditor-icon simditor-icon-outdent"></span></a></li> -->

                         <li><a class="toolbar-item toolbar-item-left" href="javascript:;" title="左对齐" data-param="left" data-wysihtml5-command="justifyLeft"><span class="simditor-icon simditor-icon-align-left"></span></a></li>
                         <li><a class="toolbar-item toolbar-item-center" href="javascript:;" title="居中" data-param="center" data-wysihtml5-command="justifyCenter"><span class="simditor-icon simditor-icon-align-center"></span></a></li>
			             
			             <li><span class="separator"></span></li>
			             <li><a class="toolbar-item toolbar-item-back" href="javascript:;" title="撤销" data-wysihtml5-command="undo"><span class="simditor-icon simditor-icon-back iconfont f18">&#xe663;</span></a></li>
                    	 <li><a class="toolbar-item toolbar-item-back" href="javascript:;" title="恢复" data-wysihtml5-command="redo"><span class="simditor-icon simditor-icon-back iconfont f18">&#xe661;</span></a></li>
			         </ul>
			     </div>
			 </div>

			 <?=$data?>