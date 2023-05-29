<?php
    
    Route::middleware('Proposal')->group(function(){
        Route::get('/proposal',[ProposalController::class,'index']);
        Route::get('/proposals',[ProposalController::class,'proposals'])->name('proposals');
    });
    Route::get('/ticket-proposal',[ProposalController::class,'ticketProposal'])->name('ticketProposal');
    Route::get('/proposal/{id}/{securityKey}',[ProposalController::class,'proposalDetail']);
    Route::get('/update-proposal/{id}/{securityKey}',[ProposalController::class,'updateProposal'])->name('updateProposal');
    Route::get('/delete-proposal/{id}/{securityKey}',[ProposalController::class,'deleteProposal'])->name('deleteProposal');

?>
