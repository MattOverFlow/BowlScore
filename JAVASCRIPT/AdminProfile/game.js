export function generateScores() {
    let scores = [];
    for (let round = 1; round <= 10; round++) {
        let time1 = Math.floor(Math.random() * 11);
        let time2 = 0;
        let type1 = 'normal';
        let type2 = 'normal';

        if (time1 === 10) { // Strike
            time2 = '';
            type1 = 'strike';
        } else {
            time2 = Math.floor(Math.random() * (11 - time1));
            if (time1 + time2 === 10) { // Spare
                type2 = 'spare';
            } else {
                if (time1 === 0) { // Gutter on first throw
                    type1 = 'gutter';
                }
                if (time2 === 0) { // Gutter on second throw
                    type2 = 'gutter';
                }
            }
        }
        let score = [{ time: time1, type: type1 }, { time: time2, type: type2 }];
        scores.push(score);
    }

    // Gestione dei lanci extra nell'ultimo frame
    let lastFrame = scores[9];
    if (lastFrame[0].time === 10) { // Strike
        lastFrame.push({ time: Math.floor(Math.random() * 11), type: 'normal' });
        lastFrame.push({ time: Math.floor(Math.random() * 11), type: 'normal' });
        lastFrame[1].type = lastFrame[1].time === 10 ? 'strike' : (lastFrame[1].time === 0 ? 'gutter' : 'normal');
        lastFrame[2].type = lastFrame[2].time === 10 ? 'strike' : (lastFrame[2].time === 0 ? 'gutter' : 'normal');
    } else if (lastFrame[0].time + lastFrame[1].time === 10) { // Spare
        lastFrame.push({ time: Math.floor(Math.random() * 11), type: 'normal' });
        lastFrame[2].type = lastFrame[2].time === 10 ? 'strike' : (lastFrame[2].time === 0 ? 'gutter' : 'normal');
    }

    return scores;
}

export function calculateTotal(scores) {
    let cumulativeTotal = 0;
    let totalScores = [];
    for (let i = 0; i < scores.length; i++) {
        let frame = scores[i];
        let frameTotal = frame[0].time + (frame[1].time || 0);

        if (frame[0].type === 'strike') { // Strike
            let nextFrame = scores[i + 1] || [{ time: 0 }, { time: 0 }];
            let nextNextFrame = scores[i + 2] || [{ time: 0 }, { time: 0 }];
            frameTotal += nextFrame[0].time + (nextFrame[1].time || nextNextFrame[0].time);
        } else if (frame[1].type === 'spare') { // Spare
            let nextFrame = scores[i + 1] || [{ time: 0 }];
            frameTotal += nextFrame[0].time;
        }

        cumulativeTotal += frameTotal;
        totalScores.push(cumulativeTotal);
    }
    return totalScores;
}

export function generateGame(numeroPartecipanti){
    let gameResults = [];

    for (let i = 0; i < numeroPartecipanti; i++) {
        // Genera i punteggi per ogni partecipante
        let scores = generateScores();
        // Calcola il totale per il partecipante
        let totalScores = calculateTotal(scores);
        // Aggiungi il risultato del partecipante alla lista
        gameResults.push({ scores, totalScores });
    }

    return gameResults;
}