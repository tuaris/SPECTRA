<?php 
    // Enable the spectra functionality
    require_once ("../lib/spectra_config.php");

    // check for a block number in the command line
    if (!isset ($argv[1]) or $argv[1] == ""){
        echo "You must provide a block height.\n\n";
        exit;
    }

    // Block hieght to start deleting from
    $delete_height_start = (int) $argv[1];

    // Get the current block height from the explorer
    $height_exp = (int) spectra_block_height ();        

    // Delete all blocks from $delete_height_start up to $height_exp
    for($block_hieght = $height_exp; $block_hieght >= $delete_height_start; $block_hieght--){
        // Retrieve the specified block hash
        $hash = getblockhash ($block_hieght);            

        // Retrieve the block and match it to the requested height 
        $block = getblock ($hash);

        if (!isset ($block["hash"])){
            echo "Unable to retrieve block " . $hash . "\n";
        }

        if ($block["height"] != $block_hieght){
            echo "Block Data Mismatch for block at height " . $block_hieght . "\n";
        }

        // Delete all data related to this block
        spectra_orphan_wipe ($hash);

        echo "Deleted " . $hash . "\n\n";
    }
?>