document.addEventListener('DOMContentLoaded', function() {
    // Handle card clicks
    const cards = document.querySelectorAll('.card-container');
    let isProcessing = false;

    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Prevent clicks if already flipped, matched, or processing another action
            if (this.classList.contains('flipped') || this.classList.contains('matched') || isProcessing) {
                return;
            }

            const cardId = this.getAttribute('data-card-id');
            if (!cardId) return;

            // Optimistic UI update: Flip immediately
            this.classList.add('flipped');
            
            // Send request to server
            fetch('/game/flip', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ card_id: cardId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    console.error(data.message);
                    this.classList.remove('flipped'); // Revert if error
                    return;
                }

                // Update stats
                updateStats(data.moves, data.pairs_found);

                if (data.status === 'match') {
                    // Mark both cards as matched
                    const card1 = document.querySelector(`.card-container[data-card-id="${data.card1_id}"]`);
                    const card2 = document.querySelector(`.card-container[data-card-id="${data.card2_id}"]`);
                    
                    if (card1) {
                        card1.classList.add('matched');
                        card1.querySelector('.card-front').classList.add('matched');
                    }
                    if (card2) {
                        card2.classList.add('matched');
                        card2.querySelector('.card-front').classList.add('matched');
                    }

                    // Check for victory
                    if (data.is_game_won && data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                } else if (data.status === 'no_match') {
                    // Wait and flip back
                    isProcessing = true; // Block input
                    setTimeout(() => {
                        const card1 = document.querySelector(`.card-container[data-card-id="${data.card1_id}"]`);
                        const card2 = document.querySelector(`.card-container[data-card-id="${data.card2_id}"]`);
                        
                        if (card1) card1.classList.remove('flipped');
                        if (card2) card2.classList.remove('flipped');
                        
                        isProcessing = false; // Unblock input
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.classList.remove('flipped');
            });
        });
    });

    function updateStats(moves, pairs) {
        // Find the stats elements (this assumes the structure in play.php)
        // We might need to add IDs to the stats spans in play.php to make this robust
        // For now, we can try to find them by text content or structure, 
        // but adding IDs is better. Let's assume we will add IDs in the next step or use a selector.
        
        // Since we didn't add IDs yet, let's try to find the strong tags
        const strongTags = document.querySelectorAll('.stats-container strong');
        if (strongTags.length >= 3) {
            // Assuming order: Pairs, Moves, Time
            // Actually, let's look at the structure in play.php:
            // Paires: <strong>X/Y</strong>
            // Coups: <strong>X</strong>
            // Temps: <strong>X</strong>
            
            // We can't easily update "Pairs: X/Y" without parsing, so let's just update Moves for now
            // or better, let's update play.php to add IDs.
        }
        
        // For now, let's just update the moves if we can find the element containing "Coups:"
        const statsDiv = document.querySelector('div[style*="font-size: 1.1rem"]');
        if (statsDiv) {
            const strongs = statsDiv.querySelectorAll('strong');
            if (strongs.length >= 2) {
                // strongs[0] is pairs (e.g. "0/6")
                // strongs[1] is moves
                
                // Update pairs
                const currentPairsText = strongs[0].innerText;
                const totalPairs = currentPairsText.split('/')[1];
                strongs[0].innerText = `${pairs}/${totalPairs}`;
                
                // Update moves
                strongs[1].innerText = moves;
            }
        }
    }
});
