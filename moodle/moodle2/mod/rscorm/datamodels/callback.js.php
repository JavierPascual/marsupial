<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

?>
   this.connectPrereqCallback = {

        success: function(o) {
            if(<?php echo $CFG->branch; ?> >= 24 )  {
                // Moodle 2.4 or higuer
                Y.use('yui2-connection','yui2-resize', 'yui2-dragdrop', 'yui2-container', 'yui2-button', 'yui2-layout', 'yui2-treeview', 'yui2-json', 'yui2-event', function(Y) {
                    YAHOO = Y.YUI2;                
                });
            }
            rscorm_tree_node = YAHOO.widget.TreeView.getTree('rscorm_tree');
            if (o.responseText !== undefined) {
                    //alert('got a response: ' + o.responseText);
                    if (rscorm_tree_node && o.responseText) {
                        var hnode = rscorm_tree_node.getHighlightedNode();
                        var hidx = null;
                        if (hnode) {
                            hidx = hnode.index + rscorm_tree_node.getNodeCount();
                        }
                        // all gone
                        var root_node = rscorm_tree_node.getRoot();
                        while (root_node.children.length > 0) {
                            rscorm_tree_node.removeNode(root_node.children[0]);
                        }
                    }
                    // make sure the temporary tree element is not there
                    var el_old_tree = document.getElementById('rscormtree123');
                    if (el_old_tree) {
                        el_old_tree.parentNode.removeChild(el_old_tree);
                    }
                    var el_new_tree = document.createElement('div');
                    var pagecontent = document.getElementById("page-content");
                    el_new_tree.setAttribute('id','rscormtree123');
                    el_new_tree.innerHTML = o.responseText;
                    // make sure it doesnt show
                    el_new_tree.style.display = 'none';
                    pagecontent.appendChild(el_new_tree)
                    // ignore the first level element as this is the title
                    var startNode = el_new_tree.firstChild.firstChild;
                    if (startNode.tagName == 'LI') {
                        // go back to the beginning
                        startNode = el_new_tree;
                    }
                    //var sXML = new XMLSerializer().serializeToString(startNode);
                    rscorm_tree_node.buildTreeFromMarkup(startNode);
                    var el = document.getElementById('rscormtree123');
                    el.parentNode.removeChild(el);
                    rscorm_tree_node.expandAll();
                    rscorm_tree_node.render();
                    if (hidx != null) {
                        hnode = rscorm_tree_node.getNodeByIndex(hidx);
                        if (hnode) {
                            hnode.highlight();
                            rscorm_layout_widget = YAHOO.widget.Layout.getLayoutById('rscorm_layout');
                            var left = rscorm_layout_widget.getUnitByPosition('left');
                            if (left.expanded) {
                                hnode.focus();
                            }
                        }
                    }
                }
        },

        failure: function(o) {
            // do some sort of error handling
            var sURL = "<?php echo $CFG->wwwroot; ?>" + "/mod/rscorm/prereqs.php?a=<?php echo $scorm->id ?>&scoid=<?php echo $scoid ?>&attempt=<?php echo $attempt ?>&mode=<?php echo $mode ?>&currentorg=<?php echo $currentorg ?>&sesskey=<?php echo sesskey(); ?>";
            //TODO: Enable this error handing correctly - avoiding issues when closing player MDL-23470 
            //alert('Prerequisites update failed - must restart RSCORM player');
            //window.location.href = sURL;
        }

    };


