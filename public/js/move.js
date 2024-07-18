let lists = document.querySelectorAll('.cardListMove')
for (let index = 0; index < lists.length; index++) {
    document.addEventListener('DOMContentLoaded', function () {
        const sortable = new Sortable(lists[index], {
            group: 'shared',
            animation: 150,
            dragClass: 'drag',
            onAdd: function () {
                let li = lists[index].querySelectorAll('li')
                for (let i = 0; i < li.length; i++) {
                    let id_card = li[i].classList[0];
                    let list_id = lists[index].classList[0]
                    fetch(`http://127.0.0.1:8000/cards/moveList/${list_id}/${id_card}`, {
                        method: 'get'
                    })
                }


            },
            onEnd: function (evt) {
                let listItems = evt.to.querySelectorAll('li');
                let cardIds = [];

                listItems.forEach((item, index) => {
                    let idCard = item.getAttribute('data-id'); // Assurez-vous que les ID sont dans un attribut data-id
                    cardIds.push(idCard);
                });

                let data = {
                    cardIds: cardIds,
                    listId: evt.to.getAttribute('class').split(' ')[0] // Utilisez l'ID de la liste depuis la classe
                };

                fetch(`http://127.0.0.1:8000/cards/move`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Cards moved successfully');
                        } else {
                            console.log('Error moving cards');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

    });
}
// let listmove = document.querySelectorAll('.listMove')
// console.log(listmove);
// for (let i = 0; i < listmove.length; i++) {
//     const list = listmove[i];
//     const sortable = new Sortable(listmove[i], {
//         group: 'shared',
//         animation: 150,
//         dragClass: 'drag',
//         onAdd: function () {
//             let idlist = []
//             let listmove = document.querySelectorAll('.listMove')

//             for (let index = 0; index < listmove.length; index++) {
//                 const list = listmove[index];
//                 idlist.push(list.classList[0])
//             }
//             fetch('http://127.0.0.1:8000/list/move', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//                 },
//                 body: JSON.stringify(idlist)
//             })
//         }
//     })
// }
