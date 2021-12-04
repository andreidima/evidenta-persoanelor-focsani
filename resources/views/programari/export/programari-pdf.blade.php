<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Programari</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        * {
            /* padding: 0;
            text-indent: 0; */
        }

        table{
            border-collapse:collapse;
            /* margin: 0px 0px; */
            /* margin-left: 5px; */
            margin-top: 0px;
            border-style: solid;
            border-width:0px;
            width: 100%;
            word-wrap:break-word;
            /* word-break: break-all; */
            /* table-layout: fixed; */
        }

        th, td {
            padding: 5px 5px;
            border-width:1px;
            border-style: solid;
            table-layout:fixed;
            font-weight: normal;

        }
        tr {
            /* text-align:; */
            /* border-style: solid;
            border-width:1px; */
        }
        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 0.5px;
        }

        /* tr:nth-child(even) {background-color:lightgray;} */
    </style>
</head>

<body>
            <div style="
                width:690px;
                min-height:600px;
                padding: 15px 10px 15px 10px;
                margin:0px 0px;
                    -moz-border-radius: 10px;
                    -webkit-border-radius: 10px;
                    border-radius: 10px;">

                <h1 style="text-align: center">
                    Programări
                </h1>
                <h2 style="text-align: center">
                    {{ \Carbon\Carbon::parse($data)->isoFormat('DD.MM.YYYY') }}
                </h2>

                <br><br>

                <table>
                        <tr>
                                <td style="text-align: center">
                                    <b>
                                        Ora
                                    </b>
                                </td>
                                <td style="text-align: center">
                                    <b>
                                        Nume
                                    </b>
                                </td>
                                <td style="text-align: center">
                                    <b>
                                        CNP
                                    </b>
                                </td>
                        </tr>
                    @foreach ($ore_de_program as $ora)
                        <tr>
                            @forelse($programari->where('ora', '=', $ora->ora) as $programare)
                                <td style="text-align: center">
                                    {{ \Carbon\Carbon::parse($ora->ora)->isoFormat('HH:mm') }}
                                </td>
                                <td style="padding: 0px 10px">
                                    {{ $programare->nume }}
                                </td>
                                <td style="text-align: center">
                                    {{ $programare->cnp }}
                                </td>
                            @empty
                                <td style="text-align: center">
                                    {{ \Carbon\Carbon::parse($ora->ora)->isoFormat('HH:mm') }}
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                            @endforelse
                        </tr>
                    @endforeach
                </table>
            </div>


</body>

</html>
