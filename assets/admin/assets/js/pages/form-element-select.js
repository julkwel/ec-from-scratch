let choices = document.querySelectorAll('.choices');
let initChoice;
const choicesIssue39Fix = (choicesElement) => {
    choicesElement.input.element.addEventListener('keydown', (keypressEvent) => {
        if (keypressEvent.keyCode === 13 && keypressEvent.target.value) {
            let keywordHits = 0;
            [...choicesElement.choiceList.element.children].forEach((element) => {
                if (element.innerText.includes(keypressEvent.target.value)) {
                    keywordHits += 1;
                }
            });

            if (!keywordHits) {
                keypressEvent.stopPropagation();
                choicesElement.setChoices([
                    {
                        value: keypressEvent.target.value,
                        label: keypressEvent.target.value,
                        selected: true,
                    },
                ], 'value', 'label', false);
                keypressEvent.target.value = '';
            }
        }
    });
};

for (let i = 0; i < choices.length; i++) {
    if (choices[i].classList.contains("multiple-remove")) {
        initChoice = new Choices(choices[i],
            {
                delimiter: ',',
                editItems: true,
                maxItemCount: -1,
                removeItemButton: true,
                addItemText: (value) => {
                    return `Press Enter to add <b>"${value}"</b>`;
                },
            });
        choicesIssue39Fix(initChoice);
    } else {
        initChoice = new Choices(choices[i]);
        choicesIssue39Fix(initChoice);
    }
}
